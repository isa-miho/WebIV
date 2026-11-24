<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemVendaRequest;
use App\Http\Requests\VendaRequest;
use App\Mail\VendaRealizada;
use App\Models\Cliente;
use App\Models\FormaPagamento;
use App\Models\Produto;
use App\Models\Venda;
use App\Models\VendaItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// Venda: cada item dá BAIXA no estoque. O valor unitário é o valor de venda do produto.
// As contas a receber são geradas de novo sempre que o total muda.
class VendaController extends Controller
{
    function listar() {
        $vendas = Venda::orderBy('data', 'desc')->orderBy('id', 'desc')
            ->with(['cliente', 'formaPagamento'])
            ->withCount('itens')
            ->paginate(10);
        return view('listagem_venda', compact('vendas'));
    }

    function novo() {
        $venda = new Venda();
        $venda->id = 0;
        $venda->documento = 'V-' . str_pad((Venda::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        $venda->cliente_id = 0;
        $venda->data = today();
        $venda->forma_pagamento_id = 0;
        $venda->valor = 0;
        return $this->formulario($venda);
    }

    function editar($id) {
        $venda = Venda::find($id);
        if ($venda->temParcelaPaga()) {
            return redirect('venda/listar')->with('erro', 'Esta venda já tem parcelas recebidas e não pode ser alterada.');
        }
        return $this->formulario($venda);
    }

    private function formulario($venda) {
        $clientes = Cliente::orderBy('nome')->get();
        $formas = FormaPagamento::orderBy('numero_parcelas')->orderBy('descricao')->get();
        return view('formulario_venda', compact('venda', 'clientes', 'formas'));
    }

    function salvar(VendaRequest $request) {
        if ($request->id == 0) {
            $venda = new Venda();
            $venda->valor = 0;
        } else {
            $venda = Venda::find($request->id);
            if ($venda->temParcelaPaga()) {
                return redirect('venda/listar')->with('erro', 'Esta venda já tem parcelas recebidas e não pode ser alterada.');
            }
        }
        $venda->documento = $request->documento;
        $venda->cliente_id = $request->cliente_id;
        $venda->data = $request->data;
        $venda->forma_pagamento_id = $request->forma_pagamento_id;
        $venda->save();

        $venda->gerarContasReceber();

        return redirect('venda/itens/'.$venda->id);
    }

    function excluir($id) {
        $venda = Venda::with('itens')->find($id);

        if ($venda->temParcelaPaga()) {
            return redirect('venda/listar')->with('erro', 'Não é possível excluir: a venda já tem parcelas recebidas.');
        }

        DB::transaction(function () use ($venda) {
            // devolve ao estoque o que a venda tinha baixado
            foreach ($venda->itens as $item) {
                Produto::where('id', $item->produto_id)->increment('quantidade_estoque', $item->quantidade);
            }
            $venda->delete(); // itens e contas a receber são excluídos junto (cascade)
        });

        return redirect('venda/listar')->with('mensagem', 'Venda excluída. Produtos devolvidos ao estoque.');
    }

    // ---------- Itens ----------

    function itens($id) {
        $venda = Venda::with(['cliente', 'formaPagamento', 'itens.produto', 'contas'])->find($id);
        $produtos = Produto::orderBy('descricao')->get();

        $item = new VendaItem();
        $item->id = 0;
        $item->venda_id = $venda->id;
        $item->produto_id = 0;
        $item->quantidade = 1;

        return view('itens_venda', compact('venda', 'produtos', 'item'));
    }

    function editarItem($id) {
        $item = VendaItem::find($id);
        $venda = Venda::with(['cliente', 'formaPagamento', 'itens.produto', 'contas'])->find($item->venda_id);
        $produtos = Produto::orderBy('descricao')->get();
        return view('itens_venda', compact('venda', 'produtos', 'item'));
    }

    function salvarItem(ItemVendaRequest $request) {
        $venda = Venda::find($request->venda_id);
        if ($venda->temParcelaPaga()) {
            return back()->withErrors('Esta venda já tem parcelas recebidas e não pode ser alterada.');
        }

        if ($request->id == 0) {
            $item = new VendaItem();
        } else {
            $item = VendaItem::find($request->id);
        }

        // quanto o estoque de cada produto vai mudar com esta alteração
        $variacao = [];
        if ($item->id) {
            $variacao[$item->produto_id] = $item->quantidade;   // devolve a quantidade anterior
        }
        $variacao[$request->produto_id] = ($variacao[$request->produto_id] ?? 0) - $request->quantidade;

        foreach ($variacao as $produto_id => $quantidade) {
            $produto = Produto::find($produto_id);
            if ($produto->quantidade_estoque + $quantidade < 0) {
                $disponivel = $produto->quantidade_estoque + ($item->produto_id == $produto_id ? $item->quantidade : 0);
                return back()->withInput()->withErrors("Estoque insuficiente de {$produto->descricao}: disponível {$disponivel}.");
            }
        }

        DB::transaction(function () use ($item, $request, $variacao, $venda) {
            foreach ($variacao as $produto_id => $quantidade) {
                Produto::where('id', $produto_id)->increment('quantidade_estoque', $quantidade);
            }

            $produto = Produto::find($request->produto_id);
            $item->venda_id = $venda->id;
            $item->produto_id = $produto->id;
            $item->quantidade = $request->quantidade;
            $item->valor_unitario = $produto->valor_venda;   // preço de venda do cadastro
            $item->valor_total = round($request->quantidade * $produto->valor_venda, 2);
            $item->save();

            $venda->atualizarTotal();
            $venda->gerarContasReceber();
        });

        return redirect('venda/itens/'.$venda->id);
    }

    function excluirItem($id) {
        $item = VendaItem::find($id);
        $venda = Venda::find($item->venda_id);

        if ($venda->temParcelaPaga()) {
            return redirect('venda/itens/'.$venda->id)->with('erro', 'Esta venda já tem parcelas recebidas e não pode ser alterada.');
        }

        DB::transaction(function () use ($item, $venda) {
            Produto::where('id', $item->produto_id)->increment('quantidade_estoque', $item->quantidade);
            $item->delete();
            $venda->atualizarTotal();
            $venda->gerarContasReceber();
        });

        return redirect('venda/itens/'.$venda->id);
    }

    // ---------- Finalizar: envia o e-mail para o cliente ----------

    function finalizar($id) {
        $venda = Venda::with(['cliente', 'formaPagamento', 'itens.produto', 'contas'])->find($id);

        if ($venda->itens->count() == 0) {
            return redirect('venda/itens/'.$id)->with('erro', 'Adicione pelo menos um produto antes de finalizar a venda.');
        }

        try {
            Mail::to($venda->cliente->email)->send(new VendaRealizada($venda));
        } catch (\Exception $e) {
            report($e);
            return redirect('venda/listar')->with('erro',
                "A venda {$venda->documento} foi salva, mas o e-mail não pôde ser enviado: " . $e->getMessage());
        }

        return redirect('venda/listar')->with('mensagem',
            "Venda {$venda->documento} finalizada. E-mail enviado para {$venda->cliente->email}.");
    }

    // Comprovante da venda em PDF
    function pdf($id) {
        $venda = Venda::with(['cliente', 'formaPagamento', 'itens.produto', 'contas'])->find($id);
        $pdf = Pdf::loadView('pdf_venda', compact('venda'));
        return $pdf->download("venda_{$venda->documento}.pdf");
    }
}

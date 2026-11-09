<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemVendaRequest;
use App\Http\Requests\VendaRequest;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Venda;
use App\Models\VendaItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

// Venda: cada item DÁ BAIXA no estoque do produto
class VendaController extends Controller
{
    function listar() {
        $vendas = Venda::orderBy('data', 'desc')
            ->orderBy('id', 'desc')
            ->with(['cliente', 'orcamento'])
            ->withCount('itens')
            ->paginate(10);
        return view('listagem_venda', compact('vendas'));
    }

    function novo() {
        $venda = new Venda();
        $venda->id = 0;
        $venda->cliente_id = 0;
        $venda->data = date('Y-m-d');
        $venda->documento = '';
        $venda->valor = 0;
        $clientes = Cliente::orderBy('nome')->get();
        return view('formulario_venda', compact('venda', 'clientes'));
    }

    function salvar(VendaRequest $request) {
        if ($request->id == 0) {
            $venda = new Venda();
            $venda->valor = 0;
        } else {
            $venda = Venda::find($request->id);
        }
        $venda->cliente_id = $request->cliente_id;
        $venda->data = $request->data;
        $venda->documento = $request->documento;
        $venda->save();

        if ($request->id == 0) {
            return redirect('venda/itens/'.$venda->id);
        } else {
            return redirect('venda/listar');
        }
    }

    function editar($id) {
        $venda = Venda::find($id);
        $clientes = Cliente::orderBy('nome')->get();
        return view('formulario_venda', compact('venda', 'clientes'));
    }

    function excluir($id) {
        $venda = Venda::with('itens')->find($id);

        DB::transaction(function () use ($venda) {
            // devolve ao estoque o que a venda tinha baixado
            foreach ($venda->itens as $item) {
                Produto::where('id', $item->produto_id)->increment('quantidade_estoque', $item->quantidade);
            }
            $venda->delete(); // itens excluídos junto (cascade)
        });

        return redirect('venda/listar')->with('mensagem', 'Venda excluída e produtos devolvidos ao estoque.');
    }

    // ---------- Itens ----------

    function itens($id) {
        $venda = Venda::with(['cliente', 'itens.produto'])->find($id);
        $produtos = Produto::orderBy('nome')->get();

        $item = new VendaItem();
        $item->id = 0;
        $item->venda_id = $venda->id;
        $item->produto_id = 0;
        $item->quantidade = 1;

        return view('itens_venda', compact('venda', 'produtos', 'item'));
    }

    function salvarItem(ItemVendaRequest $request) {
        if ($request->id == 0) {
            $item = new VendaItem();
        } else {
            $item = VendaItem::find($request->id);
        }

        // Quanto o estoque de cada produto vai mudar com esta alteração
        $variacao = [];
        if ($item->id) {
            $variacao[$item->produto_id] = $item->quantidade; // devolve a quantidade anterior
        }
        $variacao[$request->produto_id] = ($variacao[$request->produto_id] ?? 0) - $request->quantidade;

        foreach ($variacao as $produto_id => $quantidade) {
            $produto = Produto::find($produto_id);
            if ($produto->quantidade_estoque + $quantidade < 0) {
                $disponivel = $produto->quantidade_estoque + ($item->produto_id == $produto_id ? $item->quantidade : 0);
                return back()->withInput()->withErrors(
                    "Estoque insuficiente de {$produto->nome}: disponível {$disponivel}.");
            }
        }

        DB::transaction(function () use ($item, $request, $variacao) {
            foreach ($variacao as $produto_id => $quantidade) {
                Produto::where('id', $produto_id)->increment('quantidade_estoque', $quantidade);
            }
            $item->venda_id = $request->venda_id;
            $item->produto_id = $request->produto_id;
            $item->quantidade = $request->quantidade;

            // o valor é o preço de venda do cadastro do produto
            $item->valor = Produto::find($request->produto_id)->valor_venda;
            $item->save();

            $this->atualizarTotal($item->venda_id);
        });

        return redirect('venda/itens/'.$item->venda_id);
    }

    function editarItem($id) {
        $item = VendaItem::find($id);
        $venda = Venda::with(['cliente', 'itens.produto'])->find($item->venda_id);
        $produtos = Produto::orderBy('nome')->get();
        return view('itens_venda', compact('venda', 'produtos', 'item'));
    }

    function excluirItem($id) {
        $item = VendaItem::find($id);
        $venda_id = $item->venda_id;

        DB::transaction(function () use ($item, $venda_id) {
            Produto::where('id', $item->produto_id)->increment('quantidade_estoque', $item->quantidade);
            $item->delete();
            $this->atualizarTotal($venda_id);
        });

        return redirect('venda/itens/'.$venda_id);
    }

    // Comprovante da venda em PDF
    function pdf($id) {
        $venda = Venda::with(['cliente', 'itens.produto'])->find($id);
        $pdf = Pdf::loadView('pdf_venda', compact('venda'));
        return $pdf->download("venda_{$venda->id}.pdf");
    }

    private function atualizarTotal($venda_id) {
        $venda = Venda::find($venda_id);
        $venda->valor = DB::table('venda_item')
            ->where('venda_id', '=', $venda_id)
            ->sum(DB::raw('quantidade * valor'));
        $venda->save();
    }
}

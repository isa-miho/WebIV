<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemVendaRequest;
use App\Http\Requests\VendaRequest;
use App\Models\Cliente;
use App\Models\OrcamentoVenda;
use App\Models\OrcamentoVendaItem;
use App\Models\Produto;
use App\Models\Venda;
use App\Models\VendaItem;
use Illuminate\Support\Facades\DB;

class OrcamentoVendaController extends Controller
{
    function listar() {
        $orcamentos = OrcamentoVenda::orderBy('data', 'desc')
            ->orderBy('id', 'desc')
            ->with('cliente')
            ->withCount('itens')
            ->paginate(10);
        return view('listagem_orcamento_venda', compact('orcamentos'));
    }

    function novo() {
        $orcamento = new OrcamentoVenda();
        $orcamento->id = 0;
        $orcamento->cliente_id = 0;
        $orcamento->data = date('Y-m-d');
        $orcamento->documento = '';
        $orcamento->valor = 0;
        $clientes = Cliente::orderBy('nome')->get();
        return view('formulario_orcamento_venda', compact('orcamento', 'clientes'));
    }

    function salvar(VendaRequest $request) {
        if ($request->id == 0) {
            $orcamento = new OrcamentoVenda();
            $orcamento->valor = 0;
        } else {
            $orcamento = OrcamentoVenda::find($request->id);
        }
        $orcamento->cliente_id = $request->cliente_id;
        $orcamento->data = $request->data;
        $orcamento->documento = $request->documento;
        $orcamento->save();

        if ($request->id == 0) {
            return redirect('orcamento_venda/itens/'.$orcamento->id);
        } else {
            return redirect('orcamento_venda/listar');
        }
    }

    function editar($id) {
        $orcamento = OrcamentoVenda::find($id);
        $clientes = Cliente::orderBy('nome')->get();
        return view('formulario_orcamento_venda', compact('orcamento', 'clientes'));
    }

    function excluir($id) {
        OrcamentoVenda::find($id)->delete();
        return redirect('orcamento_venda/listar')->with('mensagem', 'Orçamento excluído.');
    }

    // ---------- Itens ----------

    function itens($id) {
        $orcamento = OrcamentoVenda::with(['cliente', 'itens.produto'])->find($id);
        $produtos = Produto::orderBy('nome')->get();

        $item = new OrcamentoVendaItem();
        $item->id = 0;
        $item->orcamento_venda_id = $orcamento->id;
        $item->produto_id = 0;
        $item->quantidade = 1;

        return view('itens_orcamento_venda', compact('orcamento', 'produtos', 'item'));
    }

    function salvarItem(ItemVendaRequest $request) {
        if ($request->id == 0) {
            $item = new OrcamentoVendaItem();
        } else {
            $item = OrcamentoVendaItem::find($request->id);
        }
        $item->orcamento_venda_id = $request->orcamento_venda_id;
        $item->produto_id = $request->produto_id;
        $item->quantidade = $request->quantidade;

        // o valor é o preço de venda do cadastro do produto
        $produto = Produto::find($item->produto_id);
        $item->valor = $produto->valor_venda;
        $item->save();

        $this->atualizarTotal($item->orcamento_venda_id);
        return redirect('orcamento_venda/itens/'.$item->orcamento_venda_id);
    }

    function editarItem($id) {
        $item = OrcamentoVendaItem::find($id);
        $orcamento = OrcamentoVenda::with(['cliente', 'itens.produto'])->find($item->orcamento_venda_id);
        $produtos = Produto::orderBy('nome')->get();
        return view('itens_orcamento_venda', compact('orcamento', 'produtos', 'item'));
    }

    function excluirItem($id) {
        $item = OrcamentoVendaItem::find($id);
        $orcamento_id = $item->orcamento_venda_id;
        $item->delete();

        $this->atualizarTotal($orcamento_id);
        return redirect('orcamento_venda/itens/'.$orcamento_id);
    }

    // Transforma o orçamento em uma venda (e dá baixa no estoque)
    function gerarVenda($id) {
        $orcamento = OrcamentoVenda::with('itens.produto')->find($id);

        if ($orcamento->itens->count() == 0) {
            return redirect('orcamento_venda/itens/'.$id)->with('erro', 'Adicione itens ao orçamento antes de gerar a venda.');
        }

        // confere se há estoque para todos os itens (somando o mesmo produto)
        $quantidades = [];
        foreach ($orcamento->itens as $item) {
            $quantidades[$item->produto_id] = ($quantidades[$item->produto_id] ?? 0) + $item->quantidade;
        }
        $faltando = [];
        foreach ($quantidades as $produto_id => $quantidade) {
            $produto = Produto::find($produto_id);
            if ($produto->quantidade_estoque < $quantidade) {
                $faltando[] = "{$produto->nome} (pedido: {$quantidade}, em estoque: {$produto->quantidade_estoque})";
            }
        }
        if (count($faltando) > 0) {
            return redirect('orcamento_venda/itens/'.$id)
                ->with('erro', 'Estoque insuficiente para gerar a venda: '.implode('; ', $faltando));
        }

        $venda = DB::transaction(function () use ($orcamento) {
            $venda = new Venda();
            $venda->cliente_id = $orcamento->cliente_id;
            $venda->data = date('Y-m-d');
            $venda->documento = $orcamento->documento;
            $venda->orcamento_venda_id = $orcamento->id;
            $venda->valor = $orcamento->valor;
            $venda->save();

            foreach ($orcamento->itens as $itemOrcamento) {
                $item = new VendaItem();
                $item->venda_id = $venda->id;
                $item->produto_id = $itemOrcamento->produto_id;
                $item->quantidade = $itemOrcamento->quantidade;
                $item->valor = $itemOrcamento->valor; // mantém o preço orçado
                $item->save();

                // baixa no estoque
                Produto::where('id', $item->produto_id)->decrement('quantidade_estoque', $item->quantidade);
            }
            return $venda;
        });

        return redirect('venda/itens/'.$venda->id)
            ->with('mensagem', "Venda gerada a partir do orçamento {$orcamento->documento}. O estoque foi atualizado.");
    }

    private function atualizarTotal($orcamento_id) {
        $orcamento = OrcamentoVenda::find($orcamento_id);
        $orcamento->valor = DB::table('orcamento_venda_item')
            ->where('orcamento_venda_id', '=', $orcamento_id)
            ->sum(DB::raw('quantidade * valor'));
        $orcamento->save();
    }
}

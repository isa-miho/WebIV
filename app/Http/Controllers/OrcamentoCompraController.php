<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompraRequest;
use App\Http\Requests\ItemCompraRequest;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Fornecedor;
use App\Models\OrcamentoCompra;
use App\Models\OrcamentoCompraItem;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;

class OrcamentoCompraController extends Controller
{
    function listar() {
        $orcamentos = OrcamentoCompra::orderBy('data', 'desc')
            ->orderBy('id', 'desc')
            ->with('fornecedor')
            ->withCount('itens')
            ->paginate(10);
        return view('listagem_orcamento_compra', compact('orcamentos'));
    }

    function novo() {
        $orcamento = new OrcamentoCompra();
        $orcamento->id = 0;
        $orcamento->fornecedor_id = 0;
        $orcamento->data = date('Y-m-d');
        $orcamento->documento = '';
        $orcamento->valor = 0;
        $fornecedores = Fornecedor::orderBy('razao_social')->get();
        return view('formulario_orcamento_compra', compact('orcamento', 'fornecedores'));
    }

    function salvar(CompraRequest $request) {
        if ($request->id == 0) {
            $orcamento = new OrcamentoCompra();
            $orcamento->valor = 0;
        } else {
            $orcamento = OrcamentoCompra::find($request->id);
        }
        $orcamento->fornecedor_id = $request->fornecedor_id;
        $orcamento->data = $request->data;
        $orcamento->documento = $request->documento;
        $orcamento->save();

        if ($request->id == 0) {
            return redirect('orcamento_compra/itens/'.$orcamento->id);
        } else {
            return redirect('orcamento_compra/listar');
        }
    }

    function editar($id) {
        $orcamento = OrcamentoCompra::find($id);
        $fornecedores = Fornecedor::orderBy('razao_social')->get();
        return view('formulario_orcamento_compra', compact('orcamento', 'fornecedores'));
    }

    function excluir($id) {
        // os itens são excluídos junto (cascadeOnDelete na migration)
        OrcamentoCompra::find($id)->delete();
        return redirect('orcamento_compra/listar')->with('mensagem', 'Orçamento excluído.');
    }

    // ---------- Itens ----------

    function itens($id) {
        $orcamento = OrcamentoCompra::with(['fornecedor', 'itens.produto'])->find($id);
        $produtos = Produto::orderBy('nome')->get();

        $item = new OrcamentoCompraItem();
        $item->id = 0;
        $item->orcamento_compra_id = $orcamento->id;
        $item->produto_id = 0;
        $item->quantidade = 1;
        $item->valor = '';

        return view('itens_orcamento_compra', compact('orcamento', 'produtos', 'item'));
    }

    function salvarItem(ItemCompraRequest $request) {
        if ($request->id == 0) {
            $item = new OrcamentoCompraItem();
        } else {
            $item = OrcamentoCompraItem::find($request->id);
        }
        $item->orcamento_compra_id = $request->orcamento_compra_id;
        $item->produto_id = $request->produto_id;
        $item->quantidade = $request->quantidade;
        $item->valor = $request->valor;
        $item->save();

        $this->atualizarTotal($item->orcamento_compra_id);
        return redirect('orcamento_compra/itens/'.$item->orcamento_compra_id);
    }

    function editarItem($id) {
        $item = OrcamentoCompraItem::find($id);
        $orcamento = OrcamentoCompra::with(['fornecedor', 'itens.produto'])->find($item->orcamento_compra_id);
        $produtos = Produto::orderBy('nome')->get();
        return view('itens_orcamento_compra', compact('orcamento', 'produtos', 'item'));
    }

    function excluirItem($id) {
        $item = OrcamentoCompraItem::find($id);
        $orcamento_id = $item->orcamento_compra_id;
        $item->delete();

        $this->atualizarTotal($orcamento_id);
        return redirect('orcamento_compra/itens/'.$orcamento_id);
    }

    // Transforma o orçamento em uma compra (e dá entrada no estoque)
    function gerarCompra($id) {
        $orcamento = OrcamentoCompra::with('itens')->find($id);

        if ($orcamento->itens->count() == 0) {
            return redirect('orcamento_compra/itens/'.$id)->with('erro', 'Adicione itens ao orçamento antes de gerar a compra.');
        }

        $compra = DB::transaction(function () use ($orcamento) {
            $compra = new Compra();
            $compra->fornecedor_id = $orcamento->fornecedor_id;
            $compra->data = date('Y-m-d');
            $compra->documento = $orcamento->documento;
            $compra->orcamento_compra_id = $orcamento->id;
            $compra->valor = $orcamento->valor;
            $compra->save();

            foreach ($orcamento->itens as $itemOrcamento) {
                $item = new CompraItem();
                $item->compra_id = $compra->id;
                $item->produto_id = $itemOrcamento->produto_id;
                $item->quantidade = $itemOrcamento->quantidade;
                $item->valor = $itemOrcamento->valor;
                $item->save();

                // entrada no estoque
                Produto::where('id', $item->produto_id)->increment('quantidade_estoque', $item->quantidade);
            }
            return $compra;
        });

        return redirect('compra/itens/'.$compra->id)
            ->with('mensagem', "Compra gerada a partir do orçamento {$orcamento->documento}. O estoque foi atualizado.");
    }

    // Recalcula o valor total (soma de quantidade x valor dos itens)
    private function atualizarTotal($orcamento_id) {
        $orcamento = OrcamentoCompra::find($orcamento_id);
        $orcamento->valor = DB::table('orcamento_compra_item')
            ->where('orcamento_compra_id', '=', $orcamento_id)
            ->sum(DB::raw('quantidade * valor'));
        $orcamento->save();
    }
}

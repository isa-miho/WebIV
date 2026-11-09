<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompraRequest;
use App\Http\Requests\ItemCompraRequest;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;

// Compra: cada item DÁ ENTRADA no estoque do produto
class CompraController extends Controller
{
    function listar() {
        $compras = Compra::orderBy('data', 'desc')
            ->orderBy('id', 'desc')
            ->with(['fornecedor', 'orcamento'])
            ->withCount('itens')
            ->paginate(10);
        return view('listagem_compra', compact('compras'));
    }

    function novo() {
        $compra = new Compra();
        $compra->id = 0;
        $compra->fornecedor_id = 0;
        $compra->data = date('Y-m-d');
        $compra->documento = '';
        $compra->valor = 0;
        $fornecedores = Fornecedor::orderBy('razao_social')->get();
        return view('formulario_compra', compact('compra', 'fornecedores'));
    }

    function salvar(CompraRequest $request) {
        if ($request->id == 0) {
            $compra = new Compra();
            $compra->valor = 0;
        } else {
            $compra = Compra::find($request->id);
        }
        $compra->fornecedor_id = $request->fornecedor_id;
        $compra->data = $request->data;
        $compra->documento = $request->documento;
        $compra->save();

        if ($request->id == 0) {
            return redirect('compra/itens/'.$compra->id);
        } else {
            return redirect('compra/listar');
        }
    }

    function editar($id) {
        $compra = Compra::find($id);
        $fornecedores = Fornecedor::orderBy('razao_social')->get();
        return view('formulario_compra', compact('compra', 'fornecedores'));
    }

    function excluir($id) {
        $compra = Compra::with('itens.produto')->find($id);

        // Excluir a compra tira do estoque o que ela tinha dado entrada.
        // Se algum produto já foi vendido, o estoque ficaria negativo.
        foreach ($compra->itens as $item) {
            if ($item->produto->quantidade_estoque < $item->quantidade) {
                return redirect('compra/listar')->with('erro',
                    "Não é possível excluir: o produto {$item->produto->nome} tem só {$item->produto->quantidade_estoque} em estoque e a compra deu entrada em {$item->quantidade}.");
            }
        }

        DB::transaction(function () use ($compra) {
            foreach ($compra->itens as $item) {
                Produto::where('id', $item->produto_id)->decrement('quantidade_estoque', $item->quantidade);
            }
            $compra->delete(); // itens excluídos junto (cascade)
        });

        return redirect('compra/listar')->with('mensagem', 'Compra excluída e estoque ajustado.');
    }

    // ---------- Itens ----------

    function itens($id) {
        $compra = Compra::with(['fornecedor', 'itens.produto'])->find($id);
        $produtos = Produto::orderBy('nome')->get();

        $item = new CompraItem();
        $item->id = 0;
        $item->compra_id = $compra->id;
        $item->produto_id = 0;
        $item->quantidade = 1;
        $item->valor = '';

        return view('itens_compra', compact('compra', 'produtos', 'item'));
    }

    function salvarItem(ItemCompraRequest $request) {
        if ($request->id == 0) {
            $item = new CompraItem();
        } else {
            $item = CompraItem::find($request->id);
        }

        // Quanto o estoque de cada produto vai mudar com esta alteração
        $variacao = [];
        if ($item->id) {
            $variacao[$item->produto_id] = -$item->quantidade; // desfaz a entrada anterior
        }
        $variacao[$request->produto_id] = ($variacao[$request->produto_id] ?? 0) + $request->quantidade;

        foreach ($variacao as $produto_id => $quantidade) {
            $produto = Produto::find($produto_id);
            if ($produto->quantidade_estoque + $quantidade < 0) {
                return back()->withInput()->withErrors(
                    "Não é possível alterar: o estoque de {$produto->nome} ficaria negativo.");
            }
        }

        DB::transaction(function () use ($item, $request, $variacao) {
            foreach ($variacao as $produto_id => $quantidade) {
                Produto::where('id', $produto_id)->increment('quantidade_estoque', $quantidade);
            }
            $item->compra_id = $request->compra_id;
            $item->produto_id = $request->produto_id;
            $item->quantidade = $request->quantidade;
            $item->valor = $request->valor;
            $item->save();

            $this->atualizarTotal($item->compra_id);
        });

        return redirect('compra/itens/'.$item->compra_id);
    }

    function editarItem($id) {
        $item = CompraItem::find($id);
        $compra = Compra::with(['fornecedor', 'itens.produto'])->find($item->compra_id);
        $produtos = Produto::orderBy('nome')->get();
        return view('itens_compra', compact('compra', 'produtos', 'item'));
    }

    function excluirItem($id) {
        $item = CompraItem::with('produto')->find($id);
        $compra_id = $item->compra_id;

        if ($item->produto->quantidade_estoque < $item->quantidade) {
            return redirect('compra/itens/'.$compra_id)->with('erro',
                "Não é possível remover: o estoque de {$item->produto->nome} ficaria negativo.");
        }

        DB::transaction(function () use ($item, $compra_id) {
            Produto::where('id', $item->produto_id)->decrement('quantidade_estoque', $item->quantidade);
            $item->delete();
            $this->atualizarTotal($compra_id);
        });

        return redirect('compra/itens/'.$compra_id);
    }

    private function atualizarTotal($compra_id) {
        $compra = Compra::find($compra_id);
        $compra->valor = DB::table('compra_item')
            ->where('compra_id', '=', $compra_id)
            ->sum(DB::raw('quantidade * valor'));
        $compra->save();
    }
}

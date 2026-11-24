<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompraRequest;
use App\Http\Requests\ItemCompraRequest;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\FormaPagamento;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;

// Compra: cada item dá ENTRADA no estoque e atualiza o valor de compra do produto.
// As contas a pagar são geradas de novo sempre que o total muda.
class CompraController extends Controller
{
    function listar() {
        $compras = Compra::orderBy('data', 'desc')->orderBy('id', 'desc')
            ->with(['fornecedor', 'formaPagamento'])
            ->withCount('itens')
            ->paginate(10);
        return view('listagem_compra', compact('compras'));
    }

    function novo() {
        $compra = new Compra();
        $compra->id = 0;
        $compra->documento = '';
        $compra->fornecedor_id = 0;
        $compra->data = today();
        $compra->forma_pagamento_id = 0;
        $compra->valor = 0;
        return $this->formulario($compra);
    }

    function editar($id) {
        $compra = Compra::find($id);
        if ($compra->temParcelaPaga()) {
            return redirect('compra/listar')->with('erro', 'Esta compra já tem parcelas pagas e não pode ser alterada.');
        }
        return $this->formulario($compra);
    }

    private function formulario($compra) {
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $formas = FormaPagamento::orderBy('numero_parcelas')->orderBy('descricao')->get();
        return view('formulario_compra', compact('compra', 'fornecedores', 'formas'));
    }

    function salvar(CompraRequest $request) {
        if ($request->id == 0) {
            $compra = new Compra();
            $compra->valor = 0;
        } else {
            $compra = Compra::find($request->id);
            if ($compra->temParcelaPaga()) {
                return redirect('compra/listar')->with('erro', 'Esta compra já tem parcelas pagas e não pode ser alterada.');
            }
        }
        $compra->documento = $request->documento;
        $compra->fornecedor_id = $request->fornecedor_id;
        $compra->data = $request->data;
        $compra->forma_pagamento_id = $request->forma_pagamento_id;
        $compra->save();

        // data ou forma de pagamento podem ter mudado: refaz as parcelas
        $compra->gerarContasPagar();

        return redirect('compra/itens/'.$compra->id);
    }

    function excluir($id) {
        $compra = Compra::with('itens.produto')->find($id);

        if ($compra->temParcelaPaga()) {
            return redirect('compra/listar')->with('erro', 'Não é possível excluir: a compra já tem parcelas pagas.');
        }
        // excluir a compra tira do estoque o que ela tinha dado entrada
        foreach ($compra->itens as $item) {
            if ($item->produto->quantidade_estoque < $item->quantidade) {
                return redirect('compra/listar')->with('erro',
                    "Não é possível excluir: {$item->produto->descricao} tem só {$item->produto->quantidade_estoque} em estoque e a compra deu entrada em {$item->quantidade}.");
            }
        }

        DB::transaction(function () use ($compra) {
            foreach ($compra->itens as $item) {
                Produto::where('id', $item->produto_id)->decrement('quantidade_estoque', $item->quantidade);
            }
            $compra->delete(); // itens e contas a pagar são excluídos junto (cascade)
        });

        return redirect('compra/listar')->with('mensagem', 'Compra excluída. Estoque e contas a pagar ajustados.');
    }

    // ---------- Itens ----------

    function itens($id) {
        $compra = Compra::with(['fornecedor', 'formaPagamento', 'itens.produto', 'contas'])->find($id);
        $produtos = Produto::orderBy('descricao')->get();

        $item = new CompraItem();
        $item->id = 0;
        $item->compra_id = $compra->id;
        $item->produto_id = 0;
        $item->quantidade = 1;
        $item->valor_unitario = '';

        return view('itens_compra', compact('compra', 'produtos', 'item'));
    }

    function editarItem($id) {
        $item = CompraItem::find($id);
        $compra = Compra::with(['fornecedor', 'formaPagamento', 'itens.produto', 'contas'])->find($item->compra_id);
        $produtos = Produto::orderBy('descricao')->get();
        return view('itens_compra', compact('compra', 'produtos', 'item'));
    }

    function salvarItem(ItemCompraRequest $request) {
        $compra = Compra::find($request->compra_id);
        if ($compra->temParcelaPaga()) {
            return back()->withErrors('Esta compra já tem parcelas pagas e não pode ser alterada.');
        }

        if ($request->id == 0) {
            $item = new CompraItem();
        } else {
            $item = CompraItem::find($request->id);
        }

        // quanto o estoque de cada produto vai mudar com esta alteração
        $variacao = [];
        if ($item->id) {
            $variacao[$item->produto_id] = -$item->quantidade;   // desfaz a entrada anterior
        }
        $variacao[$request->produto_id] = ($variacao[$request->produto_id] ?? 0) + $request->quantidade;

        foreach ($variacao as $produto_id => $quantidade) {
            $produto = Produto::find($produto_id);
            if ($produto->quantidade_estoque + $quantidade < 0) {
                return back()->withInput()->withErrors("Não é possível alterar: o estoque de {$produto->descricao} ficaria negativo.");
            }
        }

        DB::transaction(function () use ($item, $request, $variacao, $compra) {
            foreach ($variacao as $produto_id => $quantidade) {
                Produto::where('id', $produto_id)->increment('quantidade_estoque', $quantidade);
            }

            $item->compra_id = $compra->id;
            $item->produto_id = $request->produto_id;
            $item->quantidade = $request->quantidade;
            $item->valor_unitario = $request->valor_unitario;
            $item->valor_total = round($request->quantidade * $request->valor_unitario, 2);
            $item->save();

            // atualiza o valor de compra do produto (e o de venda, pela margem)
            $produto = Produto::with('categoria')->find($request->produto_id);
            $produto->valor_compra = $request->valor_unitario;
            $produto->atualizarValorVenda();
            $produto->save();

            $compra->atualizarTotal();
            $compra->gerarContasPagar();
        });

        return redirect('compra/itens/'.$compra->id);
    }

    function excluirItem($id) {
        $item = CompraItem::with('produto')->find($id);
        $compra = Compra::find($item->compra_id);

        if ($compra->temParcelaPaga()) {
            return redirect('compra/itens/'.$compra->id)->with('erro', 'Esta compra já tem parcelas pagas e não pode ser alterada.');
        }
        if ($item->produto->quantidade_estoque < $item->quantidade) {
            return redirect('compra/itens/'.$compra->id)->with('erro',
                "Não é possível remover: o estoque de {$item->produto->descricao} ficaria negativo.");
        }

        DB::transaction(function () use ($item, $compra) {
            Produto::where('id', $item->produto_id)->decrement('quantidade_estoque', $item->quantidade);
            $item->delete();
            $compra->atualizarTotal();
            $compra->gerarContasPagar();
        });

        return redirect('compra/itens/'.$compra->id);
    }
}

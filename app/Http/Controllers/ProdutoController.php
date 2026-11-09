<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Models\Categoria;
use App\Models\Produto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    function listar() {
        $produtos = Produto::orderBy('nome')->with('categoria')->paginate(10);
        return view('listagem_produto', compact('produtos'));
    }

    function novo() {
        $produto = new Produto();
        $produto->id = 0;
        $produto->nome = '';
        $produto->embalagem = '';
        $produto->codigo_barra = '';
        $produto->qtde_minima = 0;
        $produto->qtde_maxima = 0;
        $produto->valor_compra = '';
        $produto->valor_venda = '';
        $produto->quantidade_estoque = 0;
        $produto->categoria_id = 0;
        $categorias = Categoria::orderBy('descricao')->get();
        return view('formulario_produto', compact('produto', 'categorias'));
    }

    function salvar(ProdutoRequest $request) {
        if ($request->id == 0) {
            $produto = new Produto();
        } else {
            $produto = Produto::find($request->id);
        }
        $produto->nome               = $request->nome;
        $produto->embalagem          = $request->embalagem;
        $produto->codigo_barra       = $request->codigo_barra;
        $produto->qtde_minima        = $request->qtde_minima ?? 0;
        $produto->qtde_maxima        = $request->qtde_maxima ?? 0;
        $produto->valor_compra       = $request->valor_compra;
        $produto->valor_venda        = $request->valor_venda;
        $produto->quantidade_estoque = $request->quantidade_estoque;
        $produto->categoria_id       = $request->categoria_id;
        $produto->save();
        return redirect('produto/listar');
    }

    function editar($id) {
        $produto = Produto::find($id);
        $categorias = Categoria::orderBy('descricao')->get();
        return view('formulario_produto', compact('produto', 'categorias'));
    }

    function excluir($id) {
        $produto = Produto::find($id);

        // produto que já aparece em compra, venda ou orçamento não pode ser excluído
        $usado = DB::table('compra_item')->where('produto_id', $id)->exists()
              || DB::table('venda_item')->where('produto_id', $id)->exists()
              || DB::table('orcamento_compra_item')->where('produto_id', $id)->exists()
              || DB::table('orcamento_venda_item')->where('produto_id', $id)->exists();

        if ($usado) {
            return redirect('produto/listar')
                ->with('erro', "Não é possível excluir {$produto->nome}: o produto aparece em compras, vendas ou orçamentos.");
        }

        $produto->delete();
        return redirect('produto/listar')->with('mensagem', 'Produto excluído.');
    }

    // Relatório de estoque em PDF
    function relatorio() {
        $produtos = Produto::orderBy('nome')->with('categoria')->get();
        $pdf = Pdf::loadView('relatorio_estoque', compact('produtos'));
        return $pdf->download('estoque.pdf');
    }
}

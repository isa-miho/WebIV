<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    function listar() {
        $categorias = Categoria::orderBy('descricao')->withCount('produtos')->paginate(10);
        return view('listagem_categoria', compact('categorias'));
    }

    function novo() {
        $categoria = new Categoria();
        $categoria->id = 0;
        $categoria->descricao = '';
        $categoria->margem_lucro = '';
        return view('formulario_categoria', compact('categoria'));
    }

    function salvar(CategoriaRequest $request) {
        if ($request->id == 0) {
            $categoria = new Categoria();
        } else {
            $categoria = Categoria::find($request->id);
        }
        $margemMudou = $categoria->margem_lucro != $request->margem_lucro;

        $categoria->descricao = $request->descricao;
        $categoria->margem_lucro = $request->margem_lucro;
        $categoria->save();

        $mensagem = 'Categoria salva.';
        if ($request->id != 0 && $margemMudou) {
            $quantidade = $categoria->atualizarPrecosDosProdutos();
            $mensagem = "Categoria salva. Preço de venda atualizado em {$quantidade} produto(s).";
        }
        return redirect('categoria/listar')->with('mensagem', $mensagem);
    }

    function editar($id) {
        $categoria = Categoria::find($id);
        return view('formulario_categoria', compact('categoria'));
    }

    function excluir($id) {
        $categoria = Categoria::find($id);
        // os produtos desta categoria ficam "sem categoria" (nullOnDelete na migration)
        $categoria->delete();
        return redirect('categoria/listar')->with('mensagem', "Categoria \"{$categoria->descricao}\" excluída.");
    }

    // ---------- Alterar margem de lucro ----------

    // Mostra a categoria, os produtos e como ficará o novo preço de venda
    function margem($id) {
        $categoria = Categoria::with(['produtos' => fn ($q) => $q->orderBy('descricao')])->find($id);
        return view('formulario_margem', compact('categoria'));
    }

    function salvarMargem(Request $request) {
        $request->validate(
            ['margem_lucro' => ['required', 'numeric', 'min:0', 'max:1000']],
            [
                'margem_lucro.required' => 'Informe a nova margem de lucro',
                'margem_lucro.numeric'  => 'A Margem de lucro deve ser um número',
                'margem_lucro.min'      => 'A Margem de lucro não pode ser negativa',
                'margem_lucro.max'      => 'A Margem de lucro deve ser no máximo 1000%',
            ]
        );

        $categoria = Categoria::find($request->id);
        $antiga = $categoria->margem_lucro;
        $categoria->margem_lucro = $request->margem_lucro;
        $categoria->save();

        $quantidade = $categoria->atualizarPrecosDosProdutos();

        return redirect('categoria/listar')->with('mensagem',
            "Margem de \"{$categoria->descricao}\" alterada de " . number_format($antiga, 2, ',', '.') . '% para '
            . number_format($categoria->margem_lucro, 2, ',', '.') . "%. Preço de venda atualizado em {$quantidade} produto(s).");
    }
}

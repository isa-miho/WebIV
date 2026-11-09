<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    function listar() {
        // withCount traz junto a quantidade de produtos de cada categoria
        $categorias = Categoria::orderBy('descricao')->withCount('produtos')->paginate(10);
        return view('listagem_categoria', compact('categorias'));
    }

    function novo() {
        $categoria = new Categoria();
        $categoria->id = 0;
        $categoria->descricao = '';
        $categoria->margem_lucro = 0;
        return view('formulario_categoria', compact('categoria'));
    }

    function salvar(CategoriaRequest $request) {
        if ($request->id == 0) {
            $categoria = new Categoria();
        } else {
            $categoria = Categoria::find($request->id);
        }
        $categoria->descricao = $request->descricao;
        $categoria->margem_lucro = $request->margem_lucro ?? 0;
        $categoria->save();
        return redirect('categoria/listar');
    }

    function editar($id) {
        $categoria = Categoria::find($id);
        return view('formulario_categoria', compact('categoria'));
    }

    function excluir($id) {
        $categoria = Categoria::find($id);
        // Os produtos desta categoria ficam "sem categoria" automaticamente
        // (nullOnDelete na migration de produto)
        $categoria->delete();

        return redirect('categoria/listar')
            ->with('mensagem', "Categoria \"{$categoria->descricao}\" excluída.");
    }
}

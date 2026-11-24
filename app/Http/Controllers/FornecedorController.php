<?php

namespace App\Http\Controllers;

use App\Http\Requests\FornecedorRequest;
use App\Models\Fornecedor;

class FornecedorController extends Controller
{
    function listar() {
        $fornecedores = Fornecedor::orderBy('nome')->paginate(10);
        return view('listagem_fornecedor', compact('fornecedores'));
    }

    function novo() {
        $fornecedor = new Fornecedor();
        $fornecedor->id = 0;
        $fornecedor->nome = '';
        $fornecedor->documento = '';
        $fornecedor->email = '';
        $fornecedor->endereco = '';
        $fornecedor->telefone = '';
        return view('formulario_fornecedor', compact('fornecedor'));
    }

    function salvar(FornecedorRequest $request) {
        if ($request->id == 0) {
            $fornecedor = new Fornecedor();
        } else {
            $fornecedor = Fornecedor::find($request->id);
        }
        $fornecedor->nome = $request->nome;
        $fornecedor->documento = $request->documento;
        $fornecedor->email = $request->email;
        $fornecedor->endereco = $request->endereco;
        $fornecedor->telefone = $request->telefone;
        $fornecedor->save();
        return redirect('fornecedor/listar')->with('mensagem', 'Fornecedor salvo.');
    }

    function editar($id) {
        $fornecedor = Fornecedor::find($id);
        return view('formulario_fornecedor', compact('fornecedor'));
    }

    function excluir($id) {
        $fornecedor = Fornecedor::find($id);

        if ($fornecedor->compras()->exists()) {
            return redirect('fornecedor/listar')
                ->with('erro', "Não é possível excluir {$fornecedor->nome}: o fornecedor possui compras.");
        }

        $fornecedor->delete();
        return redirect('fornecedor/listar')->with('mensagem', 'Fornecedor excluído.');
    }
}

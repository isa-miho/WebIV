<?php

namespace App\Http\Controllers;

use App\Http\Requests\FornecedorRequest;
use App\Models\Fornecedor;

class FornecedorController extends Controller
{
    function listar() {
        $fornecedores = Fornecedor::orderBy('razao_social')->paginate(10);
        return view('listagem_fornecedor', compact('fornecedores'));
    }

    function novo() {
        $fornecedor = new Fornecedor();
        $fornecedor->id            = 0;
        $fornecedor->razao_social  = '';
        $fornecedor->nome_fantasia = '';
        $fornecedor->endereco      = '';
        $fornecedor->fone          = '';
        $fornecedor->email         = '';
        $fornecedor->cnpj          = '';
        return view('formulario_fornecedor', compact('fornecedor'));
    }

    function salvar(FornecedorRequest $request) {
        if ($request->id == 0) {
            $fornecedor = new Fornecedor();
        } else {
            $fornecedor = Fornecedor::find($request->id);
        }
        $fornecedor->razao_social  = $request->razao_social;
        $fornecedor->nome_fantasia = $request->nome_fantasia;
        $fornecedor->endereco      = $request->endereco;
        $fornecedor->fone          = $request->fone;
        $fornecedor->email         = $request->email;
        $fornecedor->cnpj          = $request->cnpj;
        $fornecedor->save();
        return redirect('fornecedor/listar');
    }

    function editar($id) {
        $fornecedor = Fornecedor::find($id);
        return view('formulario_fornecedor', compact('fornecedor'));
    }

    function excluir($id) {
        $fornecedor = Fornecedor::find($id);

        if ($fornecedor->compras()->count() > 0 || $fornecedor->orcamentosCompra()->count() > 0) {
            return redirect('fornecedor/listar')
                ->with('erro', "Não é possível excluir {$fornecedor->razao_social}: o fornecedor possui compras ou orçamentos.");
        }

        $fornecedor->delete();
        return redirect('fornecedor/listar')->with('mensagem', 'Fornecedor excluído.');
    }
}

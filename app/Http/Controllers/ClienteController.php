<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    function listar() {
        $clientes = Cliente::orderBy('nome')->paginate(10);
        return view('listagem_cliente', compact('clientes'));
    }

    function novo() {
        $cliente = new Cliente();
        $cliente->id       = 0;
        $cliente->nome     = '';
        $cliente->endereco = '';
        $cliente->telefone = '';
        $cliente->email    = '';
        $cliente->foto     = '';
        return view('formulario_cliente', compact('cliente'));
    }

    function salvar(ClienteRequest $request) {
        if ($request->id == 0) {
            $cliente = new Cliente();
            $cliente->foto = '';
        } else {
            $cliente = Cliente::find($request->id);
        }
        $cliente->nome     = $request->nome;
        $cliente->endereco = $request->endereco;
        $cliente->telefone = $request->telefone;
        $cliente->email    = $request->email;

        if ($request->hasFile('arquivo')) {
            $path = $request->arquivo->store('fotos', 'public');
            $nomeArquivo = basename($path);
            if ($cliente->foto != '') {
                Storage::disk('public')->delete('fotos/'.$cliente->foto);
            }
            $cliente->foto = $nomeArquivo;
        }

        $cliente->save();
        return redirect('cliente/listar');
    }

    function editar($id) {
        $cliente = Cliente::find($id);
        return view('formulario_cliente', compact('cliente'));
    }

    function excluir($id) {
        $cliente = Cliente::find($id);

        if ($cliente->vendas()->count() > 0 || $cliente->orcamentosVenda()->count() > 0) {
            return redirect('cliente/listar')
                ->with('erro', "Não é possível excluir {$cliente->nome}: o cliente possui vendas ou orçamentos.");
        }

        if ($cliente->foto != '') {
            Storage::disk('public')->delete('fotos/'.$cliente->foto);
        }
        $cliente->delete();
        return redirect('cliente/listar')->with('mensagem', 'Cliente excluído.');
    }
}

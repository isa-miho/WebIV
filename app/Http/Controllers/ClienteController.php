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
        $cliente->id = 0;
        $cliente->nome = '';
        $cliente->documento = '';
        $cliente->email = '';
        $cliente->endereco = '';
        $cliente->telefone = '';
        $cliente->foto = '';
        return view('formulario_cliente', compact('cliente'));
    }

    function salvar(ClienteRequest $request) {
        if ($request->id == 0) {
            $cliente = new Cliente();
            $cliente->foto = '';
        } else {
            $cliente = Cliente::find($request->id);
        }
        $cliente->nome = $request->nome;
        $cliente->documento = $request->documento;
        $cliente->email = $request->email;
        $cliente->endereco = $request->endereco;
        $cliente->telefone = $request->telefone;

        // upload da foto (fica em storage/app/public/fotos)
        if ($request->hasFile('arquivo')) {
            $path = $request->arquivo->store('fotos', 'public');
            if ($cliente->foto != '') {
                Storage::disk('public')->delete('fotos/'.$cliente->foto);
            }
            $cliente->foto = basename($path);
        }

        $cliente->save();
        return redirect('cliente/listar')->with('mensagem', 'Cliente salvo.');
    }

    function editar($id) {
        $cliente = Cliente::find($id);
        return view('formulario_cliente', compact('cliente'));
    }

    function excluir($id) {
        $cliente = Cliente::find($id);

        if ($cliente->vendas()->exists()) {
            return redirect('cliente/listar')
                ->with('erro', "Não é possível excluir {$cliente->nome}: o cliente possui vendas.");
        }

        if ($cliente->foto != '') {
            Storage::disk('public')->delete('fotos/'.$cliente->foto);
        }
        $cliente->delete();
        return redirect('cliente/listar')->with('mensagem', 'Cliente excluído.');
    }
}

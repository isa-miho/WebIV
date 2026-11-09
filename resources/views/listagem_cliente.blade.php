@extends('template')

@section('conteudo')
<h1>Listagem de Clientes</h1>
<a class='btn btn-primary mb-3' href="{{ url('cliente/novo') }}">Novo Cliente</a>
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th>Foto</th><th>ID</th><th>Nome</th>
            <th>Endereço</th><th>Telefone</th><th>E-mail</th>
            <th>Editar</th><th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
        <tr>
            <td>
                @if($cliente->foto != '')
                    <img src='{{url("storage/fotos/$cliente->foto")}}' style="width:50px;">
                @endif
            </td>
            <td>{{$cliente->id}}</td>
            <td>{{$cliente->nome}}</td>
            <td>{{$cliente->endereco}}</td>
            <td>{{$cliente->telefone}}</td>
            <td>{{$cliente->email}}</td>
            <td><a class='btn btn-primary btn-sm' href='{{url("cliente/editar/$cliente->id")}}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{url("cliente/excluir/$cliente->id")}}'><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $clientes->links() }}
@endsection

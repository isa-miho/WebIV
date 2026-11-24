@extends('template')

@section('conteudo')
<h1>Fornecedores</h1>
<a class='btn btn-primary mb-3' href="{{ url('fornecedor/novo') }}">Novo Fornecedor</a>
<table class='table table-bordered table-striped table-hover align-middle'>
    <thead>
        <tr>
            <th>ID</th><th>Nome</th><th>Documento</th><th>E-mail</th><th>Telefone</th><th>Endereço</th>
            <th>Editar</th><th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($fornecedores as $fornecedor)
        <tr>
            <td>{{ $fornecedor->id }}</td>
            <td>{{ $fornecedor->nome }}</td>
            <td>{{ $fornecedor->documento }}</td>
            <td>{{ $fornecedor->email }}</td>
            <td>{{ $fornecedor->telefone }}</td>
            <td>{{ $fornecedor->endereco }}</td>
            <td><a class='btn btn-primary btn-sm' href='{{ url("fornecedor/editar/$fornecedor->id") }}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{ url("fornecedor/excluir/$fornecedor->id") }}'><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $fornecedores->links() }}
@endsection

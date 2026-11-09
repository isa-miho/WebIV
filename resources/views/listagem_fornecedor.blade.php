@extends('template')

@section('conteudo')
<h1>Listagem de Fornecedores</h1>
<a class='btn btn-primary mb-3' href="{{ url('fornecedor/novo') }}">Novo Fornecedor</a>
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th>ID</th><th>Razão Social</th><th>Nome Fantasia</th>
            <th>CNPJ</th><th>Fone</th><th>E-mail</th>
            <th>Editar</th><th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($fornecedores as $f)
        <tr>
            <td>{{$f->id}}</td>
            <td>{{$f->razao_social}}</td>
            <td>{{$f->nome_fantasia}}</td>
            <td>{{$f->cnpj}}</td>
            <td>{{$f->fone}}</td>
            <td>{{$f->email}}</td>
            <td><a class='btn btn-primary btn-sm' href='{{url("fornecedor/editar/$f->id")}}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{url("fornecedor/excluir/$f->id")}}'><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $fornecedores->links() }}
@endsection

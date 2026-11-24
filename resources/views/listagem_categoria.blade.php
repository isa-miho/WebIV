@extends('template')

@section('conteudo')
<h1>Categorias</h1>
<a class='btn btn-primary mb-3' href="{{ url('categoria/novo') }}">Nova Categoria</a>
<table class='table table-bordered table-striped table-hover align-middle'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Margem de lucro</th>
            <th>Produtos</th>
            <th>Alterar margem</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categorias as $categoria)
            @php
                if ($categoria->produtos_count > 0) {
                    $aviso = "ATENÇÃO: a categoria \"{$categoria->descricao}\" possui {$categoria->produtos_count} produto(s).\n\n"
                           . "Os produtos NÃO serão apagados, mas ficarão sem categoria.\n\nDeseja excluir mesmo assim?";
                } else {
                    $aviso = "Deseja excluir a categoria \"{$categoria->descricao}\"?";
                }
            @endphp
            <tr>
                <td>{{ $categoria->id }}</td>
                <td>{{ $categoria->descricao }}</td>
                <td>{{ number_format($categoria->margem_lucro, 2, ',', '.') }}%</td>
                <td>{{ $categoria->produtos_count }}</td>
                <td><a class='btn btn-warning btn-sm' href='{{ url("categoria/margem/$categoria->id") }}'><i class="bi bi-percent"></i> Margem</a></td>
                <td><a class='btn btn-primary btn-sm' href='{{ url("categoria/editar/$categoria->id") }}'><i class="bi bi-pencil-square"></i></a></td>
                <td><a class='btn btn-danger btn-sm confirmar' href='{{ url("categoria/excluir/$categoria->id") }}' data-mensagem="{{ $aviso }}"><i class="bi bi-trash3-fill"></i></a></td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $categorias->links() }}
@endsection

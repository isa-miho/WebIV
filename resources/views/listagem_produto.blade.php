@extends('template')

@section('conteudo')
<h1>Produtos</h1>
<a class='btn btn-primary mb-3' href="{{ url('produto/novo') }}">Novo Produto</a>
<a class='btn btn-outline-secondary mb-3' href="{{ url('produto/relatorio') }}"><i class="bi bi-file-earmark-pdf"></i> Relatório de estoque</a>
<table class='table table-bordered table-striped table-hover align-middle'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Valor de compra</th>
            <th>Margem</th>
            <th>Valor de venda</th>
            <th>Estoque</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos as $produto)
        <tr>
            <td>{{ $produto->id }}</td>
            <td>{{ $produto->descricao }}</td>
            <td>{{ $produto->categoria->descricao ?? 'Sem categoria' }}</td>
            <td>R$ {{ number_format($produto->valor_compra, 2, ',', '.') }}</td>
            <td>{{ $produto->categoria ? number_format($produto->categoria->margem_lucro, 2, ',', '.').'%' : '-' }}</td>
            <td>R$ {{ number_format($produto->valor_venda, 2, ',', '.') }}</td>
            <td>
                {{ $produto->quantidade_estoque }}
                @if($produto->quantidade_estoque == 0)
                    <span class="badge text-bg-danger">sem estoque</span>
                @endif
            </td>
            <td><a class='btn btn-primary btn-sm' href='{{ url("produto/editar/$produto->id") }}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{ url("produto/excluir/$produto->id") }}'><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $produtos->links() }}
@endsection

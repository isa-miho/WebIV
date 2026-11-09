@extends('template')

@section('conteudo')
<h1>Vendas</h1>
<a class='btn btn-primary mb-3' href="{{ url('venda/novo') }}">Nova Venda</a>
<table class='table table-bordered table-striped table-hover align-middle'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Documento</th>
            <th>Cliente</th>
            <th>Orçamento</th>
            <th>Itens</th>
            <th>Valor total</th>
            <th>Produtos</th>
            <th>PDF</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vendas as $venda)
        <tr>
            <td>{{ $venda->id }}</td>
            <td>{{ $venda->data->format('d/m/Y') }}</td>
            <td>{{ $venda->documento }}</td>
            <td>{{ $venda->cliente->nome }}</td>
            <td>{{ $venda->orcamento->documento ?? '-' }}</td>
            <td>{{ $venda->itens_count }}</td>
            <td>R$ {{ number_format($venda->valor, 2, ',', '.') }}</td>
            <td><a class='btn btn-success btn-sm' href='{{url("venda/itens/$venda->id")}}'><i class="bi bi-list-ul"></i> Itens</a></td>
            <td><a class='btn btn-outline-secondary btn-sm' href='{{url("venda/pdf/$venda->id")}}'><i class="bi bi-file-earmark-pdf"></i></a></td>
            <td><a class='btn btn-primary btn-sm' href='{{url("venda/editar/$venda->id")}}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{url("venda/excluir/$venda->id")}}' data-mensagem="Deseja excluir esta venda? Os produtos voltarão para o estoque."><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $vendas->links() }}
@endsection

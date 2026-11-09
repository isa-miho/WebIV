@extends('template')

@section('conteudo')
<h1>Compras</h1>
<a class='btn btn-primary mb-3' href="{{ url('compra/novo') }}">Nova Compra</a>
<table class='table table-bordered table-striped table-hover align-middle'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Documento</th>
            <th>Fornecedor</th>
            <th>Orçamento</th>
            <th>Itens</th>
            <th>Valor total</th>
            <th>Produtos</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($compras as $compra)
        <tr>
            <td>{{ $compra->id }}</td>
            <td>{{ $compra->data->format('d/m/Y') }}</td>
            <td>{{ $compra->documento }}</td>
            <td>{{ $compra->fornecedor->razao_social }}</td>
            <td>{{ $compra->orcamento->documento ?? '-' }}</td>
            <td>{{ $compra->itens_count }}</td>
            <td>R$ {{ number_format($compra->valor, 2, ',', '.') }}</td>
            <td><a class='btn btn-success btn-sm' href='{{url("compra/itens/$compra->id")}}'><i class="bi bi-list-ul"></i> Itens</a></td>
            <td><a class='btn btn-primary btn-sm' href='{{url("compra/editar/$compra->id")}}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{url("compra/excluir/$compra->id")}}' data-mensagem="Deseja excluir esta compra? O estoque dos produtos será reduzido."><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $compras->links() }}
@endsection

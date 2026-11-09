@extends('template')

@section('conteudo')
<h1>Orçamentos de Compra</h1>
<a class='btn btn-primary mb-3' href="{{ url('orcamento_compra/novo') }}">Novo Orçamento</a>
<table class='table table-bordered table-striped table-hover align-middle'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Documento</th>
            <th>Fornecedor</th>
            <th>Itens</th>
            <th>Valor total</th>
            <th>Produtos</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orcamentos as $orcamento)
        <tr>
            <td>{{ $orcamento->id }}</td>
            <td>{{ $orcamento->data->format('d/m/Y') }}</td>
            <td>{{ $orcamento->documento }}</td>
            <td>{{ $orcamento->fornecedor->razao_social }}</td>
            <td>{{ $orcamento->itens_count }}</td>
            <td>R$ {{ number_format($orcamento->valor, 2, ',', '.') }}</td>
            <td><a class='btn btn-success btn-sm' href='{{url("orcamento_compra/itens/$orcamento->id")}}'><i class="bi bi-list-ul"></i> Itens</a></td>
            <td><a class='btn btn-primary btn-sm' href='{{url("orcamento_compra/editar/$orcamento->id")}}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{url("orcamento_compra/excluir/$orcamento->id")}}' data-mensagem="Deseja excluir este orçamento e todos os seus itens?"><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $orcamentos->links() }}
@endsection

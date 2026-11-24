@extends('template')

@section('conteudo')
<h1>Formas de Pagamento</h1>
<a class='btn btn-primary mb-3' href="{{ url('forma_pagamento/novo') }}">Nova Forma de Pagamento</a>
<table class='table table-bordered table-striped table-hover align-middle'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Nº de parcelas</th>
            <th>Parcelas (dias / %)</th>
            <th>Situação</th>
            <th>Parcelas</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($formas as $forma)
        <tr>
            <td>{{ $forma->id }}</td>
            <td>{{ $forma->descricao }}</td>
            <td>{{ $forma->numero_parcelas }}</td>
            <td>
                @foreach($forma->itens as $item)
                    <span class="badge text-bg-light border">{{ $item->dia }} dias: {{ number_format($item->porcentagem, 2, ',', '.') }}%</span>
                @endforeach
            </td>
            <td>
                @if($forma->completa())
                    <span class="badge text-bg-success">Pronta para uso</span>
                @else
                    <span class="badge text-bg-warning">Incompleta</span>
                @endif
            </td>
            <td><a class='btn btn-success btn-sm' href='{{ url("forma_pagamento/itens/$forma->id") }}'><i class="bi bi-list-ol"></i> Parcelas</a></td>
            <td><a class='btn btn-primary btn-sm' href='{{ url("forma_pagamento/editar/$forma->id") }}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{ url("forma_pagamento/excluir/$forma->id") }}'><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $formas->links() }}
@endsection

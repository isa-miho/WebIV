@extends('template')

@section('conteudo')
<h1>Contas a Pagar</h1>

<div class="row mb-3">
    <div class="col-md-4 mb-2">
        <div class="card border-danger h-100"><div class="card-body py-2">
            <div class="text-secondary small">Não pagas vencidas</div>
            <div class="fs-5 text-danger">R$ {{ number_format($totais['vencidas'], 2, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="card h-100"><div class="card-body py-2">
            <div class="text-secondary small">Não pagas a vencer</div>
            <div class="fs-5">R$ {{ number_format($totais['a_vencer'], 2, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="card border-success h-100"><div class="card-body py-2">
            <div class="text-secondary small">Pagas</div>
            <div class="fs-5 text-success">R$ {{ number_format($totais['pagas'], 2, ',', '.') }}</div>
        </div></div>
    </div>
</div>

<ul class="nav nav-tabs mb-3">
    @foreach(['abertas' => 'Em aberto', 'vencidas' => 'Vencidas', 'pagas' => 'Pagas', 'todas' => 'Todas'] as $chave => $rotulo)
        <li class="nav-item">
            <a class="nav-link {{ $situacao == $chave ? 'active' : '' }}" href="{{ url('contas_pagar/listar?situacao='.$chave) }}">{{ $rotulo }}</a>
        </li>
    @endforeach
</ul>

<table class='table table-bordered table-striped table-hover align-middle'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Documento</th>
            <th>Fornecedor</th>
            <th>Compra</th>
            <th>Vencimento</th>
            <th>Valor</th>
            <th>Pagamento</th>
            <th>Situação</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
        @forelse($contas as $conta)
        <tr>
            <td>{{ $conta->id }}</td>
            <td>{{ $conta->documento }}</td>
            <td>{{ $conta->fornecedor->nome }}</td>
            <td><a href="{{ url('compra/itens/'.$conta->compra_id) }}">{{ $conta->compra->documento }}</a></td>
            <td>{{ $conta->data_vencimento->format('d/m/Y') }}</td>
            <td>R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
            <td>{{ $conta->data_pagamento ? $conta->data_pagamento->format('d/m/Y') : '-' }}</td>
            <td>@include('_situacao', ['status' => $conta->status()])</td>
            <td>
                @if($conta->paga())
                    <a class='btn btn-outline-secondary btn-sm confirmar' href='{{ url("contas_pagar/estornar/$conta->id") }}'
                       data-mensagem="Desfazer a baixa da conta {{ $conta->documento }}?"><i class="bi bi-arrow-counterclockwise"></i> Estornar</a>
                @else
                    <a class='btn btn-success btn-sm' href='{{ url("contas_pagar/baixa/$conta->id") }}'><i class="bi bi-cash-coin"></i> Dar baixa</a>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center text-secondary">Nenhuma conta nesta situação.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $contas->links() }}
@endsection

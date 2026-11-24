@extends('template')

@section('conteudo')
<h1>Relatório de Contas a Receber</h1>
<p class="text-secondary">Contas das vendas feitas no período (pela data da venda).</p>
@include('_erros')

<form action="{{ url('contas_receber/relatorio') }}" method="GET" class="row align-items-end mb-4">
    <div class="col-md-3 mb-2">
        <label for="inicio" class="form-label">Vendas de</label>
        <input type="date" class="form-control" id="inicio" name="inicio" value="{{ $inicio }}">
    </div>
    <div class="col-md-3 mb-2">
        <label for="fim" class="form-label">até</label>
        <input type="date" class="form-control" id="fim" name="fim" value="{{ $fim }}">
    </div>
    <div class="col-md-6 mb-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
        <a href="{{ url('contas_receber/relatorio/pdf?inicio='.$inicio.'&fim='.$fim) }}" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-pdf"></i> Baixar PDF</a>
    </div>
</form>

<div class="row mb-3">
    <div class="col-md-3 mb-2">
        <div class="card border-danger h-100"><div class="card-body py-2">
            <div class="text-secondary small">Não pagos vencidos</div>
            <div class="fs-5 text-danger">R$ {{ number_format($totais['vencidos'], 2, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card h-100"><div class="card-body py-2">
            <div class="text-secondary small">Não pagos a vencer</div>
            <div class="fs-5">R$ {{ number_format($totais['a_vencer'], 2, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card border-success h-100"><div class="card-body py-2">
            <div class="text-secondary small">Pagos</div>
            <div class="fs-5 text-success">R$ {{ number_format($totais['pagos'], 2, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card bg-body-secondary h-100"><div class="card-body py-2">
            <div class="text-secondary small">Total do período</div>
            <div class="fs-5">R$ {{ number_format(array_sum($totais), 2, ',', '.') }}</div>
        </div></div>
    </div>
</div>

<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th>Venda</th>
            <th>Data da venda</th>
            <th>Cliente</th>
            <th>Parcela</th>
            <th>Vencimento</th>
            <th>Valor</th>
            <th>Pagamento</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
        @forelse($contas as $conta)
        <tr>
            <td><a href="{{ url('venda/itens/'.$conta->venda_id) }}">{{ $conta->venda->documento }}</a></td>
            <td>{{ $conta->venda->data->format('d/m/Y') }}</td>
            <td>{{ $conta->cliente->nome }}</td>
            <td>{{ $conta->documento }}</td>
            <td>{{ $conta->data_vencimento->format('d/m/Y') }}</td>
            <td>R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
            <td>{{ $conta->data_pagamento ? $conta->data_pagamento->format('d/m/Y') : '-' }}</td>
            <td>@include('_situacao', ['status' => $conta->status()])</td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-secondary">Nenhuma venda no período.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

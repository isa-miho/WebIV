@extends('template')

@section('conteudo')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="mb-0">Venda {{ $venda->documento }}</h1>
    <div>
        @if($venda->itens->count() > 0)
            <a href="{{ url('venda/finalizar/'.$venda->id) }}" class="btn btn-success confirmar"
               data-mensagem="Finalizar a venda e enviar os dados por e-mail para {{ $venda->cliente->email }}?"><i class="bi bi-envelope-check"></i> Finalizar e enviar e-mail</a>
            <a href="{{ url('venda/pdf/'.$venda->id) }}" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
        @endif
        <a href="{{ url('venda/listar') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <strong>Cliente:</strong> {{ $venda->cliente->nome }} &nbsp;|&nbsp;
        <strong>Data:</strong> {{ $venda->data->format('d/m/Y') }} &nbsp;|&nbsp;
        <strong>Forma de pagamento:</strong> {{ $venda->formaPagamento->descricao }} &nbsp;|&nbsp;
        <strong>Valor:</strong> R$ {{ number_format($venda->valor, 2, ',', '.') }}
    </div>
</div>

@include('_erros')

@php $bloqueada = $venda->contas->contains(fn ($c) => $c->paga()); @endphp
@if($bloqueada)
    <div class="alert alert-info">Esta venda já tem parcela(s) paga(s), por isso os produtos não podem mais ser alterados.</div>
@else
<form class='border border-primary rounded p-3 mb-4' action='{{ url("venda/itens/salvar") }}' method='POST'>
    @csrf
    <h5>{{ $item->id == 0 ? 'Adicionar produto' : 'Editar produto' }}</h5>
    <input type="hidden" name="id" value="{{ $item->id }}">
    <input type="hidden" name="venda_id" value="{{ $venda->id }}">
    <div class="row align-items-end">
        <div class="col-md-8 mb-2">
            <label for="produto_id" class="form-label">Produto</label>
            <select class='form-select' name="produto_id" id="produto_id">
                <option value="">Selecione o produto...</option>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}" {{ $produto->id == old('produto_id', $item->produto_id) ? 'selected' : '' }}>{{ $produto->descricao }} - R$ {{ number_format($produto->valor_venda, 2, ',', '.') }} (estoque: {{ $produto->quantidade_estoque }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" min="1" class="form-control" id="quantidade" name="quantidade" value="{{ old('quantidade', $item->quantidade) }}">
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg"></i> {{ $item->id == 0 ? 'Adicionar' : 'Salvar' }}</button>
            @if($item->id != 0)
                <a href="{{ url('venda/itens/'.$venda->id) }}" class="btn btn-link w-100">Cancelar edição</a>
            @endif
        </div>
    </div>
    <div class="form-text">O valor unitário é o valor de venda do produto. Cada produto <strong>dá baixa</strong> no estoque. As contas a receber são geradas automaticamente.</div>
</form>
@endif

<h5>Produtos</h5>
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor unitário</th>
            <th>Valor total</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @forelse($venda->itens as $linha)
        <tr class="{{ $linha->id == $item->id ? 'table-warning' : '' }}">
            <td>{{ $linha->produto->descricao }}</td>
            <td>{{ $linha->quantidade }}</td>
            <td>R$ {{ number_format($linha->valor_unitario, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($linha->valor_total, 2, ',', '.') }}</td>
            <td>@if(!$bloqueada)<a class='btn btn-primary btn-sm' href='{{ url("venda/item/editar/$linha->id") }}'><i class="bi bi-pencil-square"></i></a>@endif</td>
            <td>@if(!$bloqueada)<a class='btn btn-danger btn-sm confirmar' href='{{ url("venda/item/excluir/$linha->id") }}' data-mensagem="Remover este produto?"><i class="bi bi-trash3-fill"></i></a>@endif</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-secondary">Nenhum produto adicionado.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th>R$ {{ number_format($venda->valor, 2, ',', '.') }}</th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
</table>

<h5 class="mt-4">Contas a receber geradas</h5>
<table class='table table-sm table-bordered'>
    <thead>
        <tr><th>Documento</th><th>Vencimento</th><th>Valor</th><th>Situação</th><th>Pagamento</th></tr>
    </thead>
    <tbody>
        @forelse($venda->contas as $conta)
        <tr>
            <td>{{ $conta->documento }}</td>
            <td>{{ $conta->data_vencimento->format('d/m/Y') }}</td>
            <td>R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
            <td>@include('_situacao', ['status' => $conta->status()])</td>
            <td>{{ $conta->data_pagamento ? $conta->data_pagamento->format('d/m/Y') : '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center text-secondary">As parcelas aparecem aqui quando houver produtos.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection


@extends('template')

@section('conteudo')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="mb-0">Compra {{ $compra->documento }}</h1>
    <div>
        <a href="{{ url('compra/listar') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <strong>Fornecedor:</strong> {{ $compra->fornecedor->nome }} &nbsp;|&nbsp;
        <strong>Data:</strong> {{ $compra->data->format('d/m/Y') }} &nbsp;|&nbsp;
        <strong>Forma de pagamento:</strong> {{ $compra->formaPagamento->descricao }} &nbsp;|&nbsp;
        <strong>Valor:</strong> R$ {{ number_format($compra->valor, 2, ',', '.') }}
    </div>
</div>

@include('_erros')

@php $bloqueada = $compra->contas->contains(fn ($c) => $c->paga()); @endphp
@if($bloqueada)
    <div class="alert alert-info">Esta compra já tem parcela(s) paga(s), por isso os produtos não podem mais ser alterados.</div>
@else
<form class='border border-primary rounded p-3 mb-4' action='{{ url("compra/itens/salvar") }}' method='POST'>
    @csrf
    <h5>{{ $item->id == 0 ? 'Adicionar produto' : 'Editar produto' }}</h5>
    <input type="hidden" name="id" value="{{ $item->id }}">
    <input type="hidden" name="compra_id" value="{{ $compra->id }}">
    <div class="row align-items-end">
        <div class="col-md-5 mb-2">
            <label for="produto_id" class="form-label">Produto</label>
            <select class='form-select' name="produto_id" id="produto_id">
                <option value="">Selecione o produto...</option>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}" data-valor="{{ $produto->valor_compra }}" {{ $produto->id == old('produto_id', $item->produto_id) ? 'selected' : '' }}>{{ $produto->descricao }} (estoque: {{ $produto->quantidade_estoque }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" min="1" class="form-control" id="quantidade" name="quantidade" value="{{ old('quantidade', $item->quantidade) }}">
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">Valor unitário</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control dinheiro" data-alvo="valor_unitario" id="valor_display" placeholder="0,00"
                    value='{{ old("valor_unitario", $item->valor_unitario) !== "" ? number_format((float) old("valor_unitario", $item->valor_unitario), 2, ",", ".") : "" }}'>
            </div>
            <input type="hidden" name="valor_unitario" id="valor_unitario" value='{{ old("valor_unitario", $item->valor_unitario) }}'>
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg"></i> {{ $item->id == 0 ? 'Adicionar' : 'Salvar' }}</button>
            @if($item->id != 0)
                <a href="{{ url('compra/itens/'.$compra->id) }}" class="btn btn-link w-100">Cancelar edição</a>
            @endif
        </div>
    </div>
    <div class="form-text">Cada produto <strong>dá entrada</strong> no estoque e tem o <strong>valor de compra atualizado</strong> para o valor unitário informado (o valor de venda é recalculado pela margem). As contas a pagar são geradas automaticamente.</div>
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
        @forelse($compra->itens as $linha)
        <tr class="{{ $linha->id == $item->id ? 'table-warning' : '' }}">
            <td>{{ $linha->produto->descricao }}</td>
            <td>{{ $linha->quantidade }}</td>
            <td>R$ {{ number_format($linha->valor_unitario, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($linha->valor_total, 2, ',', '.') }}</td>
            <td>@if(!$bloqueada)<a class='btn btn-primary btn-sm' href='{{ url("compra/item/editar/$linha->id") }}'><i class="bi bi-pencil-square"></i></a>@endif</td>
            <td>@if(!$bloqueada)<a class='btn btn-danger btn-sm confirmar' href='{{ url("compra/item/excluir/$linha->id") }}' data-mensagem="Remover este produto?"><i class="bi bi-trash3-fill"></i></a>@endif</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-secondary">Nenhum produto adicionado.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th>R$ {{ number_format($compra->valor, 2, ',', '.') }}</th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
</table>

<h5 class="mt-4">Contas a pagar geradas</h5>
<table class='table table-sm table-bordered'>
    <thead>
        <tr><th>Documento</th><th>Vencimento</th><th>Valor</th><th>Situação</th><th>Pagamento</th></tr>
    </thead>
    <tbody>
        @forelse($compra->contas as $conta)
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

@section('scripts')
<script>
    // ao escolher o produto, sugere o último valor de compra dele
    let produto = document.getElementById('produto_id');
    if (produto) {
        produto.addEventListener('change', function () {
            let valor = this.selectedOptions[0].dataset.valor;
            if (valor) {
                document.getElementById('valor_unitario').value = parseFloat(valor).toFixed(2);
                document.getElementById('valor_display').value = formatarDinheiro(valor);
            }
        });
    }
</script>
@endsection

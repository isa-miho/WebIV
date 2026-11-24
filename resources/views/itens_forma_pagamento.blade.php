@extends('template')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Parcelas: {{ $forma->descricao }}</h1>
    <div>
        <a href="{{ url('forma_pagamento/gerar/'.$forma->id) }}" class="btn btn-outline-primary confirmar"
           data-mensagem="Apagar as parcelas atuais e gerar {{ $forma->numero_parcelas }} parcela(s) iguais?"><i class="bi bi-magic"></i> Gerar automaticamente</a>
        <a href="{{ url('forma_pagamento/listar') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>
</div>

@php $total = $forma->totalPorcentagem(); @endphp
<div class="alert {{ $forma->completa() ? 'alert-success' : 'alert-warning' }}">
    {{ $forma->itens->count() }} de {{ $forma->numero_parcelas }} parcela(s) cadastrada(s) —
    total {{ number_format($total, 2, ',', '.') }}%.
    @if($forma->completa())
        Esta forma de pagamento já pode ser usada em compras e vendas.
    @else
        Cadastre as parcelas até completar {{ $forma->numero_parcelas }} parcela(s) somando 100%.
    @endif
</div>

@include('_erros')

@if($item->id != 0 || $forma->itens->count() < $forma->numero_parcelas)
<form class='border border-primary rounded p-3 mb-4' action='{{ url("forma_pagamento/itens/salvar") }}' method='POST'>
    @csrf
    <h5>{{ $item->id == 0 ? 'Adicionar parcela' : 'Editar parcela' }}</h5>
    <input type="hidden" name="id" value="{{ $item->id }}">
    <input type="hidden" name="forma_pagamento_id" value="{{ $forma->id }}">
    <div class="row align-items-end">
        <div class="col-md-4 mb-2">
            <label for="porcentagem" class="form-label">Porcentagem do total (%)</label>
            <input type="number" step="0.01" min="0.01" max="100" class="form-control" id="porcentagem" name="porcentagem" value="{{ old('porcentagem', $item->porcentagem) }}">
        </div>
        <div class="col-md-4 mb-2">
            <label for="dia" class="form-label">Dia (dias após a compra/venda)</label>
            <input type="number" min="0" class="form-control" id="dia" name="dia" value="{{ old('dia', $item->dia) }}">
        </div>
        <div class="col-md-4 mb-2">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> {{ $item->id == 0 ? 'Adicionar' : 'Salvar' }}</button>
            @if($item->id != 0)
                <a href="{{ url('forma_pagamento/itens/'.$forma->id) }}" class="btn btn-link">Cancelar edição</a>
            @endif
        </div>
    </div>
    <div class="form-text">Dia 0 = no mesmo dia (à vista). Dia 30 = 30 dias depois da data da compra/venda.</div>
</form>
@endif

<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr><th>Parcela</th><th>Dia</th><th>Porcentagem</th><th>Exemplo em R$ 1.000,00</th><th>Editar</th><th>Excluir</th></tr>
    </thead>
    <tbody>
        @forelse($forma->itens as $i => $linha)
        <tr class="{{ $linha->id == $item->id ? 'table-warning' : '' }}">
            <td>{{ $i + 1 }}ª</td>
            <td>{{ $linha->dia == 0 ? 'à vista (dia 0)' : $linha->dia.' dias' }}</td>
            <td>{{ number_format($linha->porcentagem, 2, ',', '.') }}%</td>
            <td>R$ {{ number_format(1000 * $linha->porcentagem / 100, 2, ',', '.') }}</td>
            <td><a class='btn btn-primary btn-sm' href='{{ url("forma_pagamento/item/editar/$linha->id") }}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{ url("forma_pagamento/item/excluir/$linha->id") }}' data-mensagem="Excluir esta parcela?"><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-secondary">Nenhuma parcela cadastrada. Use "Gerar automaticamente" ou adicione acima.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

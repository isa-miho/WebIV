@extends('template')

@section('conteudo')
<h1>Baixa de conta</h1>
@include('_erros')

<div class="card mb-3">
    <div class="card-body">
        <p class="mb-1"><strong>Documento:</strong> {{ $conta->documento }}</p>
        <p class="mb-1"><strong>Cliente:</strong> {{ $conta->cliente->nome }}</p>
        <p class="mb-1"><strong>Venda:</strong> {{ $conta->venda->documento }} de {{ $conta->venda->data->format('d/m/Y') }}</p>
        <p class="mb-1"><strong>Vencimento:</strong> {{ $conta->data_vencimento->format('d/m/Y') }}
            @include('_situacao', ['status' => $conta->status()])</p>
        <p class="mb-0 fs-5"><strong>Valor:</strong> R$ {{ number_format($conta->valor, 2, ',', '.') }}</p>
    </div>
</div>

<form action='{{ url("contas_receber/baixa") }}' method='POST'>
    @csrf
    <input type="hidden" name="id" value="{{ $conta->id }}">
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="data_pagamento" class="form-label">Data do pagamento</label>
            <input type="date" class="form-control @error('data_pagamento') is-invalid @enderror" id="data_pagamento" name="data_pagamento"
                   value="{{ old('data_pagamento', today()->format('Y-m-d')) }}" max="{{ today()->format('Y-m-d') }}">
        </div>
    </div>
    <button type="submit" class="btn btn-success"><i class="bi bi-cash-coin"></i> Confirmar baixa</button>
    <a href="{{ url('contas_receber/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

@extends('template')

@section('conteudo')
<h1>Cadastro de Forma de Pagamento</h1>
@include('_erros')

<form action='{{ url("forma_pagamento/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-2 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{ $forma->id }}'>
        </div>
        <div class="col-md-6 mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" value='{{ old("descricao", $forma->descricao) }}' placeholder="Ex.: À vista, 30/60 dias, 3x sem entrada">
        </div>
        <div class="col-md-4 mb-3">
            <label for="numero_parcelas" class="form-label">Número de parcelas</label>
            <input type="number" min="1" max="24" class="form-control @error('numero_parcelas') is-invalid @enderror" id="numero_parcelas" name="numero_parcelas" value='{{ old("numero_parcelas", $forma->numero_parcelas) }}'>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ $forma->id == 0 ? 'Salvar e cadastrar parcelas' : 'Salvar' }}</button>
    <a href="{{ url('forma_pagamento/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

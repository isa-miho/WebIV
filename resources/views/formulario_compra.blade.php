@extends('template')

@section('conteudo')
<h1>Cadastro de Compra</h1>
@include('_erros')

<form action='{{ url("compra/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-2 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{ $compra->id }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="documento" class="form-label">Documento</label>
            <input type="text" class="form-control @error('documento') is-invalid @enderror" id="documento" name="documento" value='{{ old("documento", $compra->documento) }}' placeholder="Nº da nota ou pedido">
        </div>
        <div class="col-md-3 mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control @error('data') is-invalid @enderror" id="data" name="data" value='{{ old("data", $compra->data->format("Y-m-d")) }}'>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Valor</label>
            <input readonly type="text" class="form-control" value='R$ {{ number_format($compra->valor, 2, ",", ".") }}'>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-6 mb-3">
            <label for="fornecedor_id" class="form-label">Fornecedor</label>
            <select class='form-select @error('fornecedor_id') is-invalid @enderror' name="fornecedor_id" id="fornecedor_id">
                <option value="">Selecione...</option>
                @foreach($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->id }}" {{ $fornecedor->id == old("fornecedor_id", $compra->fornecedor_id) ? "selected" : "" }}>{{ $fornecedor->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="forma_pagamento_id" class="form-label">Forma de pagamento</label>
            <select class='form-select @error('forma_pagamento_id') is-invalid @enderror' name="forma_pagamento_id" id="forma_pagamento_id">
                <option value="">Selecione...</option>
                @foreach($formas as $forma)
                    <option value="{{ $forma->id }}" {{ $forma->id == old("forma_pagamento_id", $compra->forma_pagamento_id) ? "selected" : "" }}>
                        {{ $forma->descricao }} ({{ $forma->numero_parcelas }}x)
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ $compra->id == 0 ? 'Salvar e adicionar produtos' : 'Salvar' }}</button>
    <a href="{{ url('compra/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

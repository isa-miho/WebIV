@extends('template')

@section('conteudo')
<h1>Cadastro de Venda</h1>
@include('_erros')

<form action='{{ url("venda/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-2 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{ $venda->id }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="documento" class="form-label">Documento</label>
            <input type="text" class="form-control @error('documento') is-invalid @enderror" id="documento" name="documento" value='{{ old("documento", $venda->documento) }}' placeholder="Nº da nota ou pedido">
        </div>
        <div class="col-md-3 mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control @error('data') is-invalid @enderror" id="data" name="data" value='{{ old("data", $venda->data->format("Y-m-d")) }}'>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Valor</label>
            <input readonly type="text" class="form-control" value='R$ {{ number_format($venda->valor, 2, ",", ".") }}'>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-6 mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select class='form-select @error('cliente_id') is-invalid @enderror' name="cliente_id" id="cliente_id">
                <option value="">Selecione...</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $cliente->id == old("cliente_id", $venda->cliente_id) ? "selected" : "" }}>{{ $cliente->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="forma_pagamento_id" class="form-label">Forma de pagamento</label>
            <select class='form-select @error('forma_pagamento_id') is-invalid @enderror' name="forma_pagamento_id" id="forma_pagamento_id">
                <option value="">Selecione...</option>
                @foreach($formas as $forma)
                    <option value="{{ $forma->id }}" {{ $forma->id == old("forma_pagamento_id", $venda->forma_pagamento_id) ? "selected" : "" }}>
                        {{ $forma->descricao }} ({{ $forma->numero_parcelas }}x)
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ $venda->id == 0 ? 'Salvar e adicionar produtos' : 'Salvar' }}</button>
    <a href="{{ url('venda/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

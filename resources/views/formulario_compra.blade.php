@extends('template')

@section('conteudo')
<h1>Cadastro de Compra</h1>
@include('_erros')

<form action='{{ url("compra/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-1 mb-3">
            <label for="id" class="form-label">ID</label>
            <input readonly type="text" class="form-control" id="id" name="id" value='{{ $compra->id }}'>
        </div>
        <div class="col-md-3 mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" id="data" name="data" value='{{ old("data", $compra->data->format("Y-m-d")) }}'>
        </div>
        <div class="col-md-3 mb-3">
            <label for="documento" class="form-label">Documento</label>
            <input type="text" class="form-control" id="documento" name="documento" value='{{ old("documento", $compra->documento) }}'>
        </div>
        <div class="col-md-5 mb-3">
            <label for="fornecedor_id" class="form-label">Fornecedor</label>
            <select class='form-select' name="fornecedor_id" id="fornecedor_id">
                <option value="">Selecione...</option>
                @foreach($fornecedores as $fornecedor)
                    <option {{ $fornecedor->id == old("fornecedor_id", $compra->fornecedor_id) ? "selected" : "" }} value="{{ $fornecedor->id }}">{{ $fornecedor->razao_social }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-3 mb-3">
            <label class="form-label">Valor total</label>
            <input readonly type="text" class="form-control" value='R$ {{ number_format($compra->valor, 2, ",", ".") }}'>
            <div class="form-text">Calculado pelos itens.</div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">
        {{ $compra->id == 0 ? 'Salvar e adicionar itens' : 'Salvar' }}
    </button>
    <a href="{{ url('compra/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

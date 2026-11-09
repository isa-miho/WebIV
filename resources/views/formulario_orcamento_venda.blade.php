@extends('template')

@section('conteudo')
<h1>Cadastro de Orçamento de Venda</h1>
@include('_erros')

<form action='{{ url("orcamento_venda/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-1 mb-3">
            <label for="id" class="form-label">ID</label>
            <input readonly type="text" class="form-control" id="id" name="id" value='{{ $orcamento->id }}'>
        </div>
        <div class="col-md-3 mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" id="data" name="data" value='{{ old("data", $orcamento->data->format("Y-m-d")) }}'>
        </div>
        <div class="col-md-3 mb-3">
            <label for="documento" class="form-label">Documento</label>
            <input type="text" class="form-control" id="documento" name="documento" value='{{ old("documento", $orcamento->documento) }}'>
        </div>
        <div class="col-md-5 mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select class='form-select' name="cliente_id" id="cliente_id">
                <option value="">Selecione...</option>
                @foreach($clientes as $cliente)
                    <option {{ $cliente->id == old("cliente_id", $orcamento->cliente_id) ? "selected" : "" }} value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-3 mb-3">
            <label class="form-label">Valor total</label>
            <input readonly type="text" class="form-control" value='R$ {{ number_format($orcamento->valor, 2, ",", ".") }}'>
            <div class="form-text">Calculado pelos itens.</div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">
        {{ $orcamento->id == 0 ? 'Salvar e adicionar itens' : 'Salvar' }}
    </button>
    <a href="{{ url('orcamento_venda/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

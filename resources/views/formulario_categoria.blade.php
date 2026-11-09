@extends('template')

@section('conteudo')
<h1>Cadastro de Categoria</h1>
@include('_erros')

<form action='{{ url("categoria/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-2 mb-3">
            <label for="id" class="form-label">ID</label>
            <input readonly type="text" class="form-control" id="id" name="id" value='{{$categoria->id}}'>
        </div>
        <div class="col-md-6 mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" value='{{ old("descricao", $categoria->descricao) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="margem_lucro" class="form-label">Margem de lucro (%)</label>
            <input type="number" step="0.01" min="0" class="form-control" id="margem_lucro" name="margem_lucro" value='{{ old("margem_lucro", $categoria->margem_lucro) }}'>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ url('categoria/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

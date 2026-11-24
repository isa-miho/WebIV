@extends('template')

@section('conteudo')
<div class='d-flex align-items-center justify-content-between mb-2'>
    <h1>Cadastro de Fornecedor</h1>

</div>
@include('_erros')

<form action='{{ url("fornecedor/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-2 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{ $fornecedor->id }}'>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value='{{ old("nome", $fornecedor->nome) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="documento" class="form-label">Documento (CNPJ ou CPF)</label>
            <input type="text" class="form-control @error('documento') is-invalid @enderror" id="documento" name="documento" value='{{ old("documento", $fornecedor->documento) }}'>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-4 mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value='{{ old("email", $fornecedor->email) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value='{{ old("telefone", $fornecedor->telefone) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="endereco" class="form-label">Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco" value='{{ old("endereco", $fornecedor->endereco) }}'>
        </div>

    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ url('fornecedor/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

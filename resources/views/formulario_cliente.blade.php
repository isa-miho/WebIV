@extends('template')

@section('conteudo')
<div class='d-flex align-items-center justify-content-between mb-2'>
    <h1>Cadastro de Cliente</h1>
    @if($cliente->foto != '')
        <img src='{{ url("storage/fotos/$cliente->foto") }}' class="rounded border" style="width:120px;">
    @endif
</div>
@include('_erros')

<form action='{{ url("cliente/salvar") }}' method='POST' enctype="multipart/form-data">
    @csrf
    <div class='row'>
        <div class="col-md-2 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{ $cliente->id }}'>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value='{{ old("nome", $cliente->nome) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="documento" class="form-label">Documento (CPF ou CNPJ)</label>
            <input type="text" class="form-control @error('documento') is-invalid @enderror" id="documento" name="documento" value='{{ old("documento", $cliente->documento) }}'>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-4 mb-3">
            <label for="email" class="form-label">E-mail (recebe os dados das vendas)</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value='{{ old("email", $cliente->email) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value='{{ old("telefone", $cliente->telefone) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="endereco" class="form-label">Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco" value='{{ old("endereco", $cliente->endereco) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="arquivo" class="form-label">Foto</label>
            <input type="file" accept="image/*" class="form-control @error('arquivo') is-invalid @enderror" id="arquivo" name="arquivo">
            <div class="form-text">Imagem de até 2 MB.</div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ url('cliente/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

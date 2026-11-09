@extends('template')
@section('conteudo')
<div class='d-flex align-items-center justify-content-between'>
    <h1>Cadastro de Cliente</h1>
    @if($cliente->foto != '')
        <img src='{{url("storage/fotos/$cliente->foto")}}' style="width:200px;">
    @endif
</div>
@include('_erros')
<form action='{{ url("cliente/salvar") }}' method='POST' enctype="multipart/form-data">
    @csrf
    <div class='row'>
        <div class="col-md-1 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{$cliente->id}}'>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" value='{{old("nome", $cliente->nome)}}'>
            @error('nome')<div class="text-danger small">{{$message}}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Endereço</label>
            <input type="text" class="form-control" name="endereco" value='{{old("endereco", $cliente->endereco)}}'>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-4 mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" class="form-control" name="telefone" value='{{old("telefone", $cliente->telefone)}}'>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" value='{{old("email", $cliente->email)}}'>
            @error('email')<div class="text-danger small">{{$message}}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control" name="arquivo">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ url('cliente/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

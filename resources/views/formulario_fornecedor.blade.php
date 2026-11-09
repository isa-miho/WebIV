@extends('template')
@section('conteudo')
@include('_erros')
<h1>Cadastro de Fornecedor</h1>
<form action='{{ url("fornecedor/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-1 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{$fornecedor->id}}'>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">Razão Social</label>
            <input type="text" class="form-control" name="razao_social" value='{{old("razao_social", $fornecedor->razao_social)}}'>
            @error('razao_social')<div class="text-danger small">{{$message}}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nome Fantasia</label>
            <input type="text" class="form-control" name="nome_fantasia" value='{{old("nome_fantasia", $fornecedor->nome_fantasia)}}'>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-3 mb-3">
            <label class="form-label">CNPJ</label>
            <input type="text" class="form-control" name="cnpj" id="cnpj" value='{{old("cnpj", $fornecedor->cnpj)}}' placeholder="00.000.000/0000-00">
            @error('cnpj')<div class="text-danger small">{{$message}}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Fone</label>
            <input type="text" class="form-control" name="fone" value='{{old("fone", $fornecedor->fone)}}'>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" value='{{old("email", $fornecedor->email)}}'>
            @error('email')<div class="text-danger small">{{$message}}</div>@enderror
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">Endereço</label>
            <input type="text" class="form-control" name="endereco" value='{{old("endereco", $fornecedor->endereco)}}'>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ url('fornecedor/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
<script>
document.getElementById('cnpj').addEventListener('input', function() {
    let v = this.value.replace(/\D/g,'').substring(0,14);
    v = v.replace(/^(\d{2})(\d)/,'$1.$2');
    v = v.replace(/^(\d{2})\.(\d{3})(\d)/,'$1.$2.$3');
    v = v.replace(/\.(\d{3})(\d)/,'.$1/$2');
    v = v.replace(/(\d{4})(\d)/,'$1-$2');
    this.value = v;
});
</script>
@endsection

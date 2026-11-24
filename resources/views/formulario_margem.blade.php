@extends('template')

@section('conteudo')
<h1>Alterar margem de lucro</h1>
<p class="text-secondary">
    Categoria <strong>{{ $categoria->descricao }}</strong>. Ao salvar, o preço de venda de todos os
    {{ $categoria->produtos->count() }} produto(s) desta categoria será recalculado.
</p>
@include('_erros')

<form action='{{ url("categoria/margem") }}' method='POST' class="mb-4">
    @csrf
    <input type="hidden" name="id" value="{{ $categoria->id }}">
    <div class="row align-items-end">
        <div class="col-md-3 mb-2">
            <label class="form-label">Margem atual</label>
            <input readonly type="text" class="form-control" value="{{ number_format($categoria->margem_lucro, 2, ',', '.') }}%">
        </div>
        <div class="col-md-3 mb-2">
            <label for="margem_lucro" class="form-label">Nova margem (%)</label>
            <input type="number" step="0.01" min="0" class="form-control" id="margem_lucro" name="margem_lucro" value="{{ old('margem_lucro', $categoria->margem_lucro) }}">
        </div>
        <div class="col-md-6 mb-2">
            <button type="submit" class="btn btn-warning"><i class="bi bi-percent"></i> Alterar margem e preços</button>
            <a href="{{ url('categoria/listar') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>

<h5>Produtos da categoria</h5>
<table class='table table-bordered table-striped'>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Valor de compra</th>
            <th>Valor de venda atual</th>
            <th>Novo valor de venda</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categoria->produtos as $produto)
            <tr>
                <td>{{ $produto->descricao }}</td>
                <td>R$ {{ number_format($produto->valor_compra, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($produto->valor_venda, 2, ',', '.') }}</td>
                <td class="novo-preco fw-bold" data-compra="{{ $produto->valor_compra }}"></td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-secondary">Esta categoria ainda não tem produtos.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

@section('scripts')
<script>
    // mostra na hora como ficará o preço de venda com a nova margem
    function atualizarPrevia() {
        let margem = parseFloat(document.getElementById('margem_lucro').value || 0);
        document.querySelectorAll('.novo-preco').forEach(td => {
            let compra = parseFloat(td.dataset.compra);
            td.textContent = 'R$ ' + formatarDinheiro(compra * (1 + margem / 100));
        });
    }
    document.getElementById('margem_lucro').addEventListener('input', atualizarPrevia);
    atualizarPrevia();
</script>
@endsection

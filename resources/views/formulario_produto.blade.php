@extends('template')

@section('conteudo')
<h1>Cadastro de Produto</h1>
@include('_erros')

<form action='{{ url("produto/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-2 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{ $produto->id }}'>
        </div>
        <div class="col-md-6 mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" value='{{ old("descricao", $produto->descricao) }}'>
        </div>
        <div class="col-md-4 mb-3">
            <label for="categoria_id" class="form-label">Categoria</label>
            <select class='form-select @error('categoria_id') is-invalid @enderror' name="categoria_id" id="categoria_id">
                <option value="" data-margem="0">Selecione...</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" data-margem="{{ $categoria->margem_lucro }}"
                        {{ $categoria->id == old("categoria_id", $produto->categoria_id) ? "selected" : "" }}>
                        {{ $categoria->descricao }} ({{ number_format($categoria->margem_lucro, 2, ',', '.') }}%)
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-4 mb-3">
            <label class="form-label">Valor de compra</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control dinheiro @error('valor_compra') is-invalid @enderror" data-alvo="valor_compra" placeholder="0,00"
                    value='{{ old("valor_compra", $produto->valor_compra) !== "" ? number_format((float) old("valor_compra", $produto->valor_compra), 2, ",", ".") : "" }}'>
            </div>
            <input type="hidden" name="valor_compra" id="valor_compra" value='{{ old("valor_compra", $produto->valor_compra) }}'>
            <div class="form-text">É atualizado automaticamente a cada compra.</div>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Valor de venda</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input readonly type="text" class="form-control" id="valor_venda">
            </div>
            <div class="form-text">Calculado: valor de compra + margem da categoria.</div>
        </div>
        <div class="col-md-4 mb-3">
            <label for="quantidade_estoque" class="form-label">Quantidade em estoque</label>
            <input type="number" min="0" class="form-control @error('quantidade_estoque') is-invalid @enderror" id="quantidade_estoque" name="quantidade_estoque" value='{{ old("quantidade_estoque", $produto->quantidade_estoque) }}'>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ url('produto/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

@section('scripts')
<script>
    // valor de venda = valor de compra + margem de lucro da categoria
    function calcularVenda() {
        let compra = parseFloat(document.getElementById('valor_compra').value || 0);
        let margem = parseFloat(document.getElementById('categoria_id').selectedOptions[0].dataset.margem || 0);
        document.getElementById('valor_venda').value = formatarDinheiro(compra * (1 + margem / 100));
    }
    document.getElementById('categoria_id').addEventListener('change', calcularVenda);
    document.getElementById('valor_compra').addEventListener('change', calcularVenda);
    calcularVenda();
</script>
@endsection

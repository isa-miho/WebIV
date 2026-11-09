@extends('template')

@section('conteudo')
<h1>Cadastro de Produto</h1>
@include('_erros')

<form action='{{ url("produto/salvar") }}' method='POST'>
    @csrf
    <div class='row'>
        <div class="col-md-1 mb-3">
            <label class="form-label">ID</label>
            <input readonly type="text" class="form-control" name="id" value='{{$produto->id}}'>
        </div>
        <div class="col-md-5 mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" value='{{old("nome", $produto->nome)}}'>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Categoria</label>
            <select class='form-select' name="categoria_id" id="categoria_id">
                <option value="">Selecione...</option>
                @foreach($categorias as $cat)
                    <option {{ $cat->id == old("categoria_id", $produto->categoria_id) ? "selected" : "" }} value="{{$cat->id}}" data-margem="{{ $cat->margem_lucro }}">{{$cat->descricao}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Embalagem</label>
            <input type="text" class="form-control" name="embalagem" value='{{old("embalagem", $produto->embalagem)}}' placeholder="Pacote, Caixa, Unidade...">
        </div>
    </div>
    <div class='row'>
        <div class="col-md-3 mb-3">
            <label class="form-label">Código de barras</label>
            <input type="text" class="form-control" name="codigo_barra" value='{{old("codigo_barra", $produto->codigo_barra)}}'>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Valor de Compra</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control dinheiro" data-alvo="valor_compra"
                    value='{{ old("valor_compra", $produto->valor_compra) !== "" ? number_format((float) old("valor_compra", $produto->valor_compra), 2, ",", ".") : "" }}' placeholder="0,00">
            </div>
            <input type="hidden" name="valor_compra" id="valor_compra" value='{{old("valor_compra", $produto->valor_compra)}}'>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Valor de Venda</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control dinheiro" data-alvo="valor_venda" id="valor_venda_display"
                    value='{{ old("valor_venda", $produto->valor_venda) !== "" ? number_format((float) old("valor_venda", $produto->valor_venda), 2, ",", ".") : "" }}' placeholder="0,00">
            </div>
            <input type="hidden" name="valor_venda" id="valor_venda" value='{{old("valor_venda", $produto->valor_venda)}}'>
            <button type="button" class="btn btn-link btn-sm px-0" id="sugerir">Calcular pela margem da categoria</button>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-3 mb-3">
            <label class="form-label">Quantidade em Estoque</label>
            <input type="number" min="0" class="form-control" name="quantidade_estoque" value='{{old("quantidade_estoque", $produto->quantidade_estoque)}}'>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Quantidade mínima</label>
            <input type="number" min="0" class="form-control" name="qtde_minima" value='{{old("qtde_minima", $produto->qtde_minima)}}'>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Quantidade máxima</label>
            <input type="number" min="0" class="form-control" name="qtde_maxima" value='{{old("qtde_maxima", $produto->qtde_maxima)}}'>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ url('produto/listar') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

@section('scripts')
<script>
    // valor de venda = valor de compra + margem de lucro da categoria
    document.getElementById('sugerir').addEventListener('click', function () {
        let opcao = document.getElementById('categoria_id').selectedOptions[0];
        let compra = parseFloat(document.getElementById('valor_compra').value || 0);
        if (!opcao.value || compra == 0) {
            alert('Escolha a categoria e informe o valor de compra.');
            return;
        }
        let venda = (compra * (1 + parseFloat(opcao.dataset.margem) / 100)).toFixed(2);
        document.getElementById('valor_venda').value = venda;
        document.getElementById('valor_venda_display').value = formatarDinheiro(venda);
    });
</script>
@endsection

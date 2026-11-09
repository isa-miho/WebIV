@extends('template')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Compra #{{ $compra->id }}</h1>
    <div>
        <a href="{{ url('compra/listar') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <strong>Fornecedor:</strong> {{ $compra->fornecedor->razao_social }} &nbsp;|&nbsp;
        <strong>Data:</strong> {{ $compra->data->format('d/m/Y') }} &nbsp;|&nbsp;
        <strong>Documento:</strong> {{ $compra->documento }} &nbsp;|&nbsp;
        <strong>Total:</strong> R$ {{ number_format($compra->valor, 2, ',', '.') }}
        @if($compra->orcamento)
            &nbsp;|&nbsp; <strong>Orçamento:</strong> {{ $compra->orcamento->documento }}
        @endif
    </div>
</div>

@include('_erros')

<form class='border border-primary rounded p-3 mb-4' action='{{ url("compra/itens/salvar") }}' method='POST'>
    @csrf
    <h5>{{ $item->id == 0 ? 'Adicionar item' : 'Editar item' }}</h5>
    <input type="hidden" name="id" value="{{ $item->id }}">
    <input type="hidden" name="compra_id" value="{{ $compra->id }}">
    <div class="row align-items-end">
        <div class="col-md-5 mb-2">
            <label class="form-label">Produto</label>
            <select class='form-select' name="produto_id" id="produto_id">
                <option value="">Selecione o produto...</option>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}" data-valor="{{ $produto->valor_compra }}" {{ $produto->id == old('produto_id', $item->produto_id) ? 'selected' : '' }}>{{ $produto->nome }} (estoque: {{ $produto->quantidade_estoque }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label class="form-label">Quantidade</label>
            <input type="number" min="1" class="form-control" name="quantidade" value="{{ old('quantidade', $item->quantidade) }}">
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">Valor unitário</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control dinheiro" data-alvo="valor" id="valor_display" placeholder="0,00"
                    value='{{ old("valor", $item->valor) !== "" ? number_format((float) old("valor", $item->valor), 2, ",", ".") : "" }}'>
            </div>
            <input type="hidden" name="valor" id="valor" value='{{ old("valor", $item->valor) }}'>
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> {{ $item->id == 0 ? 'Adicionar' : 'Salvar' }}
            </button>
            @if($item->id != 0)
                <a href="{{ url('compra/itens/'.$compra->id) }}" class="btn btn-link w-100">Cancelar edição</a>
            @endif
        </div>
    </div>
    <div class="form-text">O valor unitário é preenchido com o valor de compra do produto, mas pode ser alterado. Cada item adicionado <strong>dá entrada</strong> no estoque.</div>
</form>

<h5>Itens</h5>
<table class='table table-bordered table-striped table-hover'>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor unitário</th>
            <th>Subtotal</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        @forelse($compra->itens as $linha)
        <tr class="{{ $linha->id == $item->id ? 'table-warning' : '' }}">
            <td>{{ $linha->produto->nome }}</td>
            <td>{{ $linha->quantidade }}</td>
            <td>R$ {{ number_format($linha->valor, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($linha->subtotal(), 2, ',', '.') }}</td>
            <td><a class='btn btn-primary btn-sm' href='{{ url("compra/item/editar/$linha->id") }}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{ url("compra/item/excluir/$linha->id") }}' data-mensagem="Remover este item?"><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Nenhum item adicionado.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th>R$ {{ number_format($compra->valor, 2, ',', '.') }}</th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
</table>
@endsection

@section('scripts')
<script>
    // ao escolher o produto, preenche o valor unitário com o valor de compra dele
    document.getElementById('produto_id').addEventListener('change', function () {
        let valor = this.selectedOptions[0].dataset.valor;
        if (valor) {
            document.getElementById('valor').value = parseFloat(valor).toFixed(2);
            document.getElementById('valor_display').value = formatarDinheiro(valor);
        }
    });
</script>
@endsection

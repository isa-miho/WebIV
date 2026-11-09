@extends('template')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Orçamento de Venda #{{ $orcamento->id }}</h1>
    <div>
        <a href='{{ url("orcamento_venda/gerar_venda/$orcamento->id") }}' class="btn btn-success confirmar" data-mensagem="Gerar venda a partir deste orçamento? O estoque será atualizado."><i class="bi bi-check2-circle"></i> Gerar venda</a>
        <a href="{{ url('orcamento_venda/listar') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <strong>Cliente:</strong> {{ $orcamento->cliente->nome }} &nbsp;|&nbsp;
        <strong>Data:</strong> {{ $orcamento->data->format('d/m/Y') }} &nbsp;|&nbsp;
        <strong>Documento:</strong> {{ $orcamento->documento }} &nbsp;|&nbsp;
        <strong>Total:</strong> R$ {{ number_format($orcamento->valor, 2, ',', '.') }}
    </div>
</div>

@include('_erros')

<form class='border border-primary rounded p-3 mb-4' action='{{ url("orcamento_venda/itens/salvar") }}' method='POST'>
    @csrf
    <h5>{{ $item->id == 0 ? 'Adicionar item' : 'Editar item' }}</h5>
    <input type="hidden" name="id" value="{{ $item->id }}">
    <input type="hidden" name="orcamento_venda_id" value="{{ $orcamento->id }}">
    <div class="row align-items-end">
        <div class="col-md-8 mb-2">
            <label class="form-label">Produto</label>
            <select class='form-select' name="produto_id" id="produto_id">
                <option value="">Selecione o produto...</option>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}" {{ $produto->id == old('produto_id', $item->produto_id) ? 'selected' : '' }}>{{ $produto->nome }} - R$ {{ number_format($produto->valor_venda, 2, ',', '.') }} (estoque: {{ $produto->quantidade_estoque }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label class="form-label">Quantidade</label>
            <input type="number" min="1" class="form-control" name="quantidade" value="{{ old('quantidade', $item->quantidade) }}">
        </div>

        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> {{ $item->id == 0 ? 'Adicionar' : 'Salvar' }}
            </button>
            @if($item->id != 0)
                <a href="{{ url('orcamento_venda/itens/'.$orcamento->id) }}" class="btn btn-link w-100">Cancelar edição</a>
            @endif
        </div>
    </div>
    <div class="form-text">O valor unitário é o valor de venda do cadastro do produto. Orçamentos não alteram o estoque.</div>
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
        @forelse($orcamento->itens as $linha)
        <tr class="{{ $linha->id == $item->id ? 'table-warning' : '' }}">
            <td>{{ $linha->produto->nome }}</td>
            <td>{{ $linha->quantidade }}</td>
            <td>R$ {{ number_format($linha->valor, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($linha->subtotal(), 2, ',', '.') }}</td>
            <td><a class='btn btn-primary btn-sm' href='{{ url("orcamento_venda/item/editar/$linha->id") }}'><i class="bi bi-pencil-square"></i></a></td>
            <td><a class='btn btn-danger btn-sm confirmar' href='{{ url("orcamento_venda/item/excluir/$linha->id") }}' data-mensagem="Remover este item?"><i class="bi bi-trash3-fill"></i></a></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Nenhum item adicionado.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th>R$ {{ number_format($orcamento->valor, 2, ',', '.') }}</th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
</table>
@endsection


@extends('template')

@section('conteudo')
    <h1>Sistema de Controle de Estoque</h1>
    <p>Trabalho da disciplina <strong>Construção de Páginas Web IV</strong> — Sistemas para Internet 2026.2</p>
    <hr>

    <h5 class="mt-4">Cadastros</h5>
    <div class="row">
        @foreach([
            ['Produtos', 'produto/listar', 'bi-box-seam', 'primary'],
            ['Categorias', 'categoria/listar', 'bi-tags', 'primary'],
            ['Fornecedores', 'fornecedor/listar', 'bi-truck', 'primary'],
            ['Clientes', 'cliente/listar', 'bi-people', 'primary'],
        ] as [$titulo, $link, $icone, $cor])
            <div class="col-6 col-md-3 mb-3">
                <a href="{{ url($link) }}" class="card text-center text-decoration-none h-100">
                    <div class="card-body">
                        <i class="bi {{ $icone }} fs-1 text-{{ $cor }}"></i>
                        <h6 class="card-title mt-2 mb-0">{{ $titulo }}</h6>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <h5 class="mt-2">Movimentação</h5>
    <div class="row">
        @foreach([
            ['Orçamento de Compra', 'orcamento_compra/listar', 'bi-clipboard', 'success'],
            ['Compra', 'compra/listar', 'bi-cart-plus', 'success'],
            ['Orçamento de Venda', 'orcamento_venda/listar', 'bi-clipboard-check', 'warning'],
            ['Venda', 'venda/listar', 'bi-cart-check', 'warning'],
        ] as [$titulo, $link, $icone, $cor])
            <div class="col-6 col-md-3 mb-3">
                <a href="{{ url($link) }}" class="card text-center text-decoration-none h-100">
                    <div class="card-body">
                        <i class="bi {{ $icone }} fs-1 text-{{ $cor }}"></i>
                        <h6 class="card-title mt-2 mb-0">{{ $titulo }}</h6>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection

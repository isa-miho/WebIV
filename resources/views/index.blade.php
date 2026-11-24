@extends('template')

@section('conteudo')
    <h1>Sistema de Compras e Vendas</h1>
    <p class="text-secondary">Construção de Páginas Web IV — Sistemas para Internet 2026.2</p>
    <hr>

    @guest
        <p>Entre com seu usuário para usar o sistema.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Entrar</a>
        <a href="{{ route('register') }}" class="btn btn-outline-secondary">Cadastrar usuário</a>
    @endguest

    @auth
        @php
            $pagarVencidas = \App\Models\ContaPagar::where('situacao', 'aberta')->whereDate('data_vencimento', '<', today());
            $receberVencidas = \App\Models\ContaReceber::whereNull('data_pagamento')->whereDate('data_vencimento', '<', today());
        @endphp
        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <a href="{{ url('contas_pagar/listar?situacao=vencidas') }}" class="card text-decoration-none border-danger h-100">
                    <div class="card-body">
                        <div class="text-secondary">Contas a pagar vencidas</div>
                        <div class="fs-4 text-danger">R$ {{ number_format($pagarVencidas->sum('valor'), 2, ',', '.') }}</div>
                        <small class="text-secondary">{{ $pagarVencidas->count() }} parcela(s)</small>
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-2">
                <a href="{{ url('contas_receber/listar?situacao=vencidas') }}" class="card text-decoration-none border-warning h-100">
                    <div class="card-body">
                        <div class="text-secondary">Contas a receber vencidas</div>
                        <div class="fs-4 text-warning-emphasis">R$ {{ number_format($receberVencidas->sum('valor'), 2, ',', '.') }}</div>
                        <small class="text-secondary">{{ $receberVencidas->count() }} parcela(s)</small>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            @foreach([
                ['Nova venda', 'venda/novo', 'bi-cart-check', 'success'],
                ['Nova compra', 'compra/novo', 'bi-cart-plus', 'primary'],
                ['Produtos', 'produto/listar', 'bi-box-seam', 'secondary'],
                ['Relatório a receber', 'contas_receber/relatorio', 'bi-graph-up', 'secondary'],
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
    @endauth
@endsection

<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compras e Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body { min-height: 75rem; padding-top: 4.5rem; }
    </style>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ url('') }}"><i class="bi bi-shop"></i> Compras e Vendas</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Cadastros</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ url('categoria/listar') }}">Categoria</a></li>
              <li><a class="dropdown-item" href="{{ url('produto/listar') }}">Produto</a></li>
              <li><a class="dropdown-item" href="{{ url('fornecedor/listar') }}">Fornecedor</a></li>
              <li><a class="dropdown-item" href="{{ url('cliente/listar') }}">Cliente</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="{{ url('forma_pagamento/listar') }}">Forma de pagamento</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Compras</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ url('compra/listar') }}">Compra</a></li>
              <li><a class="dropdown-item" href="{{ url('contas_pagar/listar') }}">Contas a pagar</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Vendas</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ url('venda/listar') }}">Venda</a></li>
              <li><a class="dropdown-item" href="{{ url('contas_receber/listar') }}">Contas a receber</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Relatórios</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ url('contas_receber/relatorio') }}">Contas a receber por período</a></li>
              <li><a class="dropdown-item" href="{{ url('produto/relatorio') }}">Estoque (PDF)</a></li>
            </ul>
          </li>
          @endauth
        </ul>

        <ul class="navbar-nav">
          @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Meu perfil</a></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item">Sair</button>
                </form>
              </li>
            </ul>
          </li>
          @endauth
          @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Entrar</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Cadastrar</a></li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
  <main class="container">
    <div class="bg-body-tertiary p-4 rounded mt-2">
      @if(session('mensagem'))
          <div class="alert alert-success">{{ session('mensagem') }}</div>
      @endif
      @if(session('erro'))
          <div class="alert alert-danger">{{ session('erro') }}</div>
      @endif

      @yield('conteudo')
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Confirmação antes de excluir (links com a classe .confirmar)
    document.querySelectorAll('.confirmar').forEach(link => link.addEventListener('click', function (event) {
        let mensagem = this.dataset.mensagem || 'Deseja excluir este registro?';
        if (!confirm(mensagem)) {
            event.preventDefault();
        }
    }));

    // Máscara de dinheiro: campo visível ".dinheiro" + campo escondido (data-alvo) que vai para o servidor
    function formatarDinheiro(valor) {
        let centavos = Math.round(parseFloat(valor || 0) * 100).toString().padStart(3, '0');
        let inteiros = (centavos.slice(0, -2).replace(/^0+/, '') || '0').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return inteiros + ',' + centavos.slice(-2);
    }
    document.querySelectorAll('.dinheiro').forEach(campo => {
        let escondido = document.getElementById(campo.dataset.alvo);
        campo.addEventListener('input', function () {
            let digitos = campo.value.replace(/\D/g, '').padStart(3, '0');
            let valor = (parseInt(digitos) / 100).toFixed(2);
            campo.value = formatarDinheiro(valor);
            escondido.value = valor;
            escondido.dispatchEvent(new Event('change'));
        });
    });
  </script>
  @yield('scripts')
  </body>
</html>

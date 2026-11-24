<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Venda {{ $venda->documento }}</title>
    @include('_pdf_estilo')
</head>
<body>
    <h1>Comprovante de Venda {{ $venda->documento }}</h1>
    <div class="sub">Gerado em {{ now()->format('d/m/Y H:i') }}</div>

    <div class="caixa">
        <p>Data: <strong>{{ $venda->data->format('d/m/Y') }}</strong></p>
        <p>Cliente: <strong>{{ $venda->cliente->nome }}</strong> — {{ $venda->cliente->documento }}</p>
        <p>E-mail: {{ $venda->cliente->email }} &nbsp; Telefone: {{ $venda->cliente->telefone }}</p>
        <p>Forma de pagamento: <strong>{{ $venda->formaPagamento->descricao }}</strong></p>
    </div>

    @include('_tabela_venda')
</body>
</html>

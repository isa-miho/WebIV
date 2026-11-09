<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Venda {{ $venda->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        .caixa { border: 1px solid #999; padding: 10px; margin-bottom: 14px; }
        .caixa p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px 6px; }
        th { background: #e9ecef; text-align: left; }
        .num { text-align: right; }
        tfoot td { font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <h1>Comprovante de Venda nº {{ $venda->id }}</h1>

    <div class="caixa">
        <p>Data: <strong>{{ $venda->data->format('d/m/Y') }}</strong></p>
        <p>Documento: <strong>{{ $venda->documento }}</strong></p>
        <p>Cliente: <strong>{{ $venda->cliente->nome }}</strong></p>
        <p>Telefone: <strong>{{ $venda->cliente->telefone }}</strong> &nbsp; E-mail: <strong>{{ $venda->cliente->email }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th class="num">Quantidade</th>
                <th class="num">Valor unitário</th>
                <th class="num">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venda->itens as $item)
                <tr>
                    <td>{{ $item->produto->nome }}</td>
                    <td class="num">{{ $item->quantidade }}</td>
                    <td class="num">R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                    <td class="num">R$ {{ number_format($item->subtotal(), 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="num">Total</td>
                <td class="num">R$ {{ number_format($venda->valor, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

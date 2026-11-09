<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estoque</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        .data { color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; }
        th { background: #e9ecef; text-align: left; }
        .num { text-align: right; }
        .baixo { background: #fff3cd; }
        tfoot td { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Relatório de Estoque</h1>
    <div class="data">Gerado em {{ date('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produto</th>
                <th>Categoria</th>
                <th class="num">Estoque</th>
                <th class="num">Mínimo</th>
                <th class="num">Vlr. Compra</th>
                <th class="num">Vlr. Venda</th>
                <th class="num">Total em estoque</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($produtos as $produto)
                @php $total += $produto->quantidade_estoque * $produto->valor_compra; @endphp
                <tr class="{{ $produto->estoqueBaixo() ? 'baixo' : '' }}">
                    <td>{{ $produto->id }}</td>
                    <td>{{ $produto->nome }}</td>
                    <td>{{ $produto->categoria->descricao ?? 'Sem categoria' }}</td>
                    <td class="num">{{ $produto->quantidade_estoque }}</td>
                    <td class="num">{{ $produto->qtde_minima }}</td>
                    <td class="num">R$ {{ number_format($produto->valor_compra, 2, ',', '.') }}</td>
                    <td class="num">R$ {{ number_format($produto->valor_venda, 2, ',', '.') }}</td>
                    <td class="num">R$ {{ number_format($produto->quantidade_estoque * $produto->valor_compra, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="num">Valor total do estoque (pelo custo)</td>
                <td class="num">R$ {{ number_format($total, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    <p>Linhas em amarelo: produtos abaixo da quantidade mínima.</p>
</body>
</html>

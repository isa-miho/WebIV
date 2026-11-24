<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estoque</title>
    @include('_pdf_estilo')
</head>
<body>
    <h1>Relatório de Estoque</h1>
    <div class="sub">Gerado em {{ now()->format('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produto</th>
                <th>Categoria</th>
                <th class="num">Estoque</th>
                <th class="num">Valor de compra</th>
                <th class="num">Valor de venda</th>
                <th class="num">Total em estoque</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($produtos as $produto)
                @php $total += $produto->quantidade_estoque * $produto->valor_compra; @endphp
                <tr>
                    <td>{{ $produto->id }}</td>
                    <td>{{ $produto->descricao }}</td>
                    <td>{{ $produto->categoria->descricao ?? 'Sem categoria' }}</td>
                    <td class="num">{{ $produto->quantidade_estoque }}</td>
                    <td class="num">R$ {{ number_format($produto->valor_compra, 2, ',', '.') }}</td>
                    <td class="num">R$ {{ number_format($produto->valor_venda, 2, ',', '.') }}</td>
                    <td class="num">R$ {{ number_format($produto->quantidade_estoque * $produto->valor_compra, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="num">Valor total do estoque (pelo custo)</td>
                <td class="num">R$ {{ number_format($total, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

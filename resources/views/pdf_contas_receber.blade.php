<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Contas a Receber</title>
    @include('_pdf_estilo')
</head>
<body>
    <h1>Relatório de Contas a Receber</h1>
    <div class="sub">
        Vendas de {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
        — gerado em {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <tr>
            <th>Não pagos vencidos</th>
            <th>Não pagos a vencer</th>
            <th>Pagos</th>
            <th>Total do período</th>
        </tr>
        <tr>
            <td class="num vencida">R$ {{ number_format($totais['vencidos'], 2, ',', '.') }}</td>
            <td class="num">R$ {{ number_format($totais['a_vencer'], 2, ',', '.') }}</td>
            <td class="num paga">R$ {{ number_format($totais['pagos'], 2, ',', '.') }}</td>
            <td class="num total">R$ {{ number_format(array_sum($totais), 2, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Venda</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Parcela</th>
                <th>Vencimento</th>
                <th class="num">Valor</th>
                <th>Pagamento</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contas as $conta)
                @php $status = $conta->status(); @endphp
                <tr>
                    <td>{{ $conta->venda->documento }}</td>
                    <td>{{ $conta->venda->data->format('d/m/Y') }}</td>
                    <td>{{ $conta->cliente->nome }}</td>
                    <td>{{ $conta->documento }}</td>
                    <td>{{ $conta->data_vencimento->format('d/m/Y') }}</td>
                    <td class="num">R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
                    <td>{{ $conta->data_pagamento ? $conta->data_pagamento->format('d/m/Y') : '-' }}</td>
                    <td class="{{ $status == 'Vencida' ? 'vencida' : ($status == 'Paga' ? 'paga' : '') }}">{{ $status }}</td>
                </tr>
            @empty
                <tr><td colspan="8">Nenhuma venda no período.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

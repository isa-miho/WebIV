{{-- Tabela de produtos e parcelas da venda (usada no PDF e no e-mail) --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
    <thead>
        <tr>
            <th style="border:1px solid #999; padding:5px; background:#e9ecef; text-align:left;">Produto</th>
            <th style="border:1px solid #999; padding:5px; background:#e9ecef; text-align:right;">Qtd.</th>
            <th style="border:1px solid #999; padding:5px; background:#e9ecef; text-align:right;">Valor unitário</th>
            <th style="border:1px solid #999; padding:5px; background:#e9ecef; text-align:right;">Valor total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venda->itens as $item)
        <tr>
            <td style="border:1px solid #999; padding:5px;">{{ $item->produto->descricao }}</td>
            <td style="border:1px solid #999; padding:5px; text-align:right;">{{ $item->quantidade }}</td>
            <td style="border:1px solid #999; padding:5px; text-align:right;">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
            <td style="border:1px solid #999; padding:5px; text-align:right;">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="border:1px solid #999; padding:5px; text-align:right; font-weight:bold;">Total</td>
            <td style="border:1px solid #999; padding:5px; text-align:right; font-weight:bold;">R$ {{ number_format($venda->valor, 2, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

<p style="margin:0 0 6px 0;"><strong>Parcelas ({{ $venda->formaPagamento->descricao }})</strong></p>
<table style="width:100%; border-collapse:collapse;">
    <thead>
        <tr>
            <th style="border:1px solid #999; padding:5px; background:#e9ecef; text-align:left;">Parcela</th>
            <th style="border:1px solid #999; padding:5px; background:#e9ecef; text-align:left;">Vencimento</th>
            <th style="border:1px solid #999; padding:5px; background:#e9ecef; text-align:right;">Valor</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venda->contas as $conta)
        <tr>
            <td style="border:1px solid #999; padding:5px;">{{ $conta->documento }}</td>
            <td style="border:1px solid #999; padding:5px;">{{ $conta->data_vencimento->format('d/m/Y') }}</td>
            <td style="border:1px solid #999; padding:5px; text-align:right;">R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="font-family: Arial, Helvetica, sans-serif; font-size:14px; color:#222; max-width:640px; padding:1.5rem;">
    <p>Olá, <strong>{{ $venda->cliente->nome }}</strong>!</p>
    <p>Obrigado pela sua compra. Seguem os dados da venda:</p>

    <table style="margin-bottom:14px;">
        <tr><td style="padding:2px 12px 2px 0; color:#666;">Documento</td><td><strong>{{ $venda->documento }}</strong></td></tr>
        <tr><td style="padding:2px 12px 2px 0; color:#666;">Data</td><td>{{ $venda->data->format('d/m/Y') }}</td></tr>
        <tr><td style="padding:2px 12px 2px 0; color:#666;">Forma de pagamento</td><td>{{ $venda->formaPagamento->descricao }}</td></tr>
        <tr><td style="padding:2px 12px 2px 0; color:#666;">Valor total</td><td><strong>R$ {{ number_format($venda->valor, 2, ',', '.') }}</strong></td></tr>
    </table>

    @include('_tabela_venda')

    <p style="margin-top:18px; color:#666;">Este é um e-mail automático do sistema de Compras e Vendas.</p>
</div>

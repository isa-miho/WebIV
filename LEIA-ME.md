# Sistema de Compras e Vendas — Web IV 2026.2

## Como rodar

```bash
cd estoque
php artisan storage:link      # só na primeira vez (para as fotos dos clientes aparecerem)
php artisan serve
```

Acesse http://localhost:8000 e entre com:

- **E-mail:** admin@admin.com
- **Senha:** 12345678

O banco SQLite (`database/database.sqlite`) já vem com dados de exemplo.
Para recriar o banco do zero com os dados de exemplo:

```bash
php artisan migrate:fresh --seed
```

## Onde está cada item do enunciado

| Item | Onde |
|---|---|
| Categoria com margem de lucro | Cadastros > Categoria |
| Opção para alterar a margem e atualizar o preço de venda dos produtos | Botão **Margem** na lista de categorias (mostra a prévia dos novos preços) |
| Valor de venda do produto = valor de compra + margem da categoria | Calculado automaticamente (Produto, Compra e Categoria) |
| Forma de pagamento com parcelas (porcentagem, dia) | Cadastros > Forma de pagamento > **Parcelas** |
| Compra atualiza valor de compra e estoque do produto | Compras > Compra > Itens |
| Compra gera contas a pagar conforme o parcelamento | Parcelas aparecem no fim da tela de itens da compra |
| Baixa de contas a pagar | Compras > Contas a pagar > **Dar baixa** |
| Venda atualiza o estoque e gera contas a receber | Vendas > Venda > Itens |
| Baixa de contas a receber | Vendas > Contas a receber > **Dar baixa** |
| E-mail para o cliente a cada venda | Botão **Finalizar e enviar e-mail** na tela de itens da venda |
| Relatório de contas a receber por período (data da venda) com totais de vencidos, a vencer e pagos | Relatórios > Contas a receber por período (tela e PDF) |
| 1) Banco SQLite | `.env` → `DB_CONNECTION=sqlite` |
| 2) Upload de arquivo | Foto do cliente |
| 3) Validação de formulários | `app/Http/Requests` |
| 4) Paginação de 10 em 10 | Todas as listagens |
| 5) Autenticação (Breeze) | Login, cadastro de usuário e perfil |

## E-mail

Por padrão o e-mail é gravado em `storage/logs/laravel.log` (`MAIL_MAILER=log` no `.env`).
Para enviar de verdade, coloque no `.env` os dados de SMTP (Gmail) usados no projeto do professor.

## Regras importantes

- Compra ou venda com alguma parcela já paga não pode mais ser alterada nem excluída
  (desfaça a baixa em "Estornar" se precisar).
- Não dá para vender mais do que há em estoque.
- A forma de pagamento só pode ser usada quando as parcelas somam 100%.
- Excluir uma compra tira do estoque o que ela tinha dado entrada; excluir uma venda devolve ao estoque.

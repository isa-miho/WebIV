<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\FormaPagamento;
use App\Models\FormaPagamentoItem;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use App\Models\VendaItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Dados de exemplo. Rode com: php artisan migrate:fresh --seed
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- usuário para entrar no sistema ----------
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
        ]);

        // ---------- categorias ----------
        $categorias = [];
        foreach ([['Alimentos', 40], ['Bebidas', 60], ['Limpeza', 80], ['Higiene', 70], ['Eletrônicos', 30]] as [$descricao, $margem]) {
            $categoria = new Categoria();
            $categoria->descricao = $descricao;
            $categoria->margem_lucro = $margem;
            $categoria->save();
            $categorias[$descricao] = $categoria;
        }

        // ---------- produtos (valor de venda calculado pela margem) ----------
        $produtos = [];
        foreach ([
            ['Arroz 5kg', 18.50, 40, 'Alimentos'],
            ['Feijão 1kg', 7.80, 35, 'Alimentos'],
            ['Café 500g', 14.90, 25, 'Alimentos'],
            ['Açúcar 1kg', 4.20, 30, 'Alimentos'],
            ['Refrigerante 2L', 5.00, 48, 'Bebidas'],
            ['Suco de uva 1L', 8.50, 20, 'Bebidas'],
            ['Água mineral 1,5L', 1.60, 60, 'Bebidas'],
            ['Detergente 500ml', 2.50, 50, 'Limpeza'],
            ['Sabão em pó 1kg', 11.90, 18, 'Limpeza'],
            ['Sabonete 90g', 1.80, 40, 'Higiene'],
            ['Creme dental 90g', 3.90, 30, 'Higiene'],
            ['Fone de ouvido', 25.00, 12, 'Eletrônicos'],
        ] as [$descricao, $compra, $estoque, $categoria]) {
            $produto = new Produto();
            $produto->descricao = $descricao;
            $produto->valor_compra = $compra;
            $produto->quantidade_estoque = $estoque;
            $produto->categoria_id = $categorias[$categoria]->id;
            $produto->valor_venda = Produto::calcularValorVenda($compra, $categorias[$categoria]->margem_lucro);
            $produto->save();
            $produtos[$descricao] = $produto;
        }

        // ---------- fornecedores e clientes ----------
        $fornecedores = [];
        foreach ([
            ['Distribuidora ABC Ltda', '12.345.678/0001-99', 'contato@abc.com.br', 'Av. Principal, 100', '(67) 3333-1111'],
            ['Comércio XYZ S/A', '98.765.432/0001-11', 'vendas@xyz.com.br', 'Rua Central, 200', '(67) 3333-2222'],
            ['Atacadão Centro-Oeste', '45.678.912/0001-33', 'pedidos@atacadaoco.com.br', 'Rod. BR-163, km 5', '(67) 3333-3333'],
        ] as [$nome, $documento, $email, $endereco, $telefone]) {
            $fornecedor = new Fornecedor();
            $fornecedor->nome = $nome;
            $fornecedor->documento = $documento;
            $fornecedor->email = $email;
            $fornecedor->endereco = $endereco;
            $fornecedor->telefone = $telefone;
            $fornecedor->save();
            $fornecedores[] = $fornecedor;
        }

        $clientes = [];
        foreach ([
            ['Anderson Silva', '123.456.789-00', 'anderson@exemplo.com', 'Rua Rui Barbosa, 123', '(67) 99999-8888'],
            ['Bianca Souza', '987.654.321-00', 'bianca@exemplo.com', 'Av. Afonso Pena, 456', '(67) 99988-7766'],
            ['Carlos Alberto', '456.789.123-00', 'carlos@exemplo.com', 'Rua Ceará, 333', '(67) 99977-5543'],
            ['Daniela Rocha', '321.654.987-00', 'daniela@exemplo.com', 'Rua 14 de Julho, 900', '(67) 99966-4321'],
        ] as [$nome, $documento, $email, $endereco, $telefone]) {
            $cliente = new Cliente();
            $cliente->nome = $nome;
            $cliente->documento = $documento;
            $cliente->email = $email;
            $cliente->endereco = $endereco;
            $cliente->telefone = $telefone;
            $cliente->foto = '';
            $cliente->save();
            $clientes[] = $cliente;
        }

        // ---------- formas de pagamento: [descrição, [[dia, %], ...]] ----------
        $formas = [];
        foreach ([
            ['À vista', [[0, 100]]],
            ['30/60 dias', [[30, 50], [60, 50]]],
            ['3x (30/60/90)', [[30, 33.33], [60, 33.33], [90, 33.34]]],
            ['Entrada + 30 dias', [[0, 50], [30, 50]]],
        ] as [$descricao, $parcelas]) {
            $forma = new FormaPagamento();
            $forma->descricao = $descricao;
            $forma->numero_parcelas = count($parcelas);
            $forma->save();
            foreach ($parcelas as [$dia, $porcentagem]) {
                $item = new FormaPagamentoItem();
                $item->forma_pagamento_id = $forma->id;
                $item->dia = $dia;
                $item->porcentagem = $porcentagem;
                $item->save();
            }
            $formas[$descricao] = $forma;
        }

        // ---------- compras (dão entrada no estoque e geram contas a pagar) ----------
        $compra1 = $this->compra('NF-1001', $fornecedores[0], today()->subDays(60), $formas['30/60 dias'], [
            ['Arroz 5kg', 20, 18.90], ['Feijão 1kg', 30, 7.95], ['Café 500g', 15, 15.20],
        ]);
        $compra2 = $this->compra('NF-2045', $fornecedores[1], today()->subDays(12), $formas['3x (30/60/90)'], [
            ['Refrigerante 2L', 48, 5.10], ['Detergente 500ml', 24, 2.45], ['Sabonete 90g', 60, 1.75],
        ]);
        $this->compra('NF-3310', $fornecedores[2], today()->subDays(2), $formas['À vista'], [
            ['Fone de ouvido', 10, 24.00],
        ]);
        // a 1ª parcela da compra 1 já foi paga
        $conta = $compra1->contas()->first();
        $conta->data_pagamento = $conta->data_vencimento;
        $conta->situacao = 'paga';
        $conta->save();

        // ---------- vendas (baixam o estoque e geram contas a receber) ----------
        $v1 = $this->venda('V-0001', $clientes[0], today()->subDays(50), $formas['3x (30/60/90)'], [
            ['Arroz 5kg', 2], ['Café 500g', 3], ['Fone de ouvido', 1],
        ]);
        $v2 = $this->venda('V-0002', $clientes[1], today()->subDays(40), $formas['À vista'], [
            ['Refrigerante 2L', 6], ['Água mineral 1,5L', 12],
        ]);
        $this->venda('V-0003', $clientes[2], today()->subDays(35), $formas['30/60 dias'], [
            ['Sabão em pó 1kg', 2], ['Detergente 500ml', 4], ['Creme dental 90g', 3],
        ]);
        $this->venda('V-0004', $clientes[3], today()->subDays(18), $formas['30/60 dias'], [
            ['Feijão 1kg', 5], ['Açúcar 1kg', 4], ['Suco de uva 1L', 6],
        ]);
        $v5 = $this->venda('V-0005', $clientes[1], today()->subDays(4), $formas['Entrada + 30 dias'], [
            ['Fone de ouvido', 2], ['Sabonete 90g', 10],
        ]);

        // recebimentos: 1ª parcela da V-0001, a V-0002 (à vista) e a entrada da V-0005
        foreach ([$v1->contas()->first(), $v2->contas()->first(), $v5->contas()->first()] as $conta) {
            $conta->data_pagamento = $conta->data_vencimento;
            $conta->save();
        }
    }

    // Cria a compra como a tela faz: entrada no estoque, novo valor de compra e contas a pagar
    private function compra($documento, $fornecedor, $data, $forma, $itens): Compra
    {
        $compra = new Compra();
        $compra->documento = $documento;
        $compra->fornecedor_id = $fornecedor->id;
        $compra->data = $data;
        $compra->forma_pagamento_id = $forma->id;
        $compra->valor = 0;
        $compra->save();

        foreach ($itens as [$descricao, $quantidade, $valorUnitario]) {
            $produto = Produto::with('categoria')->where('descricao', $descricao)->first();

            $item = new CompraItem();
            $item->compra_id = $compra->id;
            $item->produto_id = $produto->id;
            $item->quantidade = $quantidade;
            $item->valor_unitario = $valorUnitario;
            $item->valor_total = round($quantidade * $valorUnitario, 2);
            $item->save();

            $produto->quantidade_estoque += $quantidade;
            $produto->valor_compra = $valorUnitario;
            $produto->atualizarValorVenda();
            $produto->save();
        }

        $compra->atualizarTotal();
        $compra->gerarContasPagar();
        return $compra;
    }

    // Cria a venda como a tela faz: baixa no estoque, preço de venda do produto e contas a receber
    private function venda($documento, $cliente, $data, $forma, $itens): Venda
    {
        $venda = new Venda();
        $venda->documento = $documento;
        $venda->cliente_id = $cliente->id;
        $venda->data = $data;
        $venda->forma_pagamento_id = $forma->id;
        $venda->valor = 0;
        $venda->save();

        foreach ($itens as [$descricao, $quantidade]) {
            $produto = Produto::where('descricao', $descricao)->first();

            $item = new VendaItem();
            $item->venda_id = $venda->id;
            $item->produto_id = $produto->id;
            $item->quantidade = $quantidade;
            $item->valor_unitario = $produto->valor_venda;
            $item->valor_total = round($quantidade * $produto->valor_venda, 2);
            $item->save();

            $produto->quantidade_estoque -= $quantidade;
            $produto->save();
        }

        $venda->atualizarTotal();
        $venda->gerarContasReceber();
        return $venda;
    }
}

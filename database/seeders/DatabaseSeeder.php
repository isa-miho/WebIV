<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    // Dados de exemplo. Rode com: php artisan migrate:fresh --seed
    public function run(): void
    {
        DB::table('categoria')->insert([
            ['descricao' => 'Alimentos',   'margem_lucro' => 40],
            ['descricao' => 'Bebidas',     'margem_lucro' => 60],
            ['descricao' => 'Limpeza',     'margem_lucro' => 80],
            ['descricao' => 'Higiene',     'margem_lucro' => 70],
            ['descricao' => 'Eletrônicos', 'margem_lucro' => 30],
        ]);

        DB::table('fornecedor')->insert([
            ['razao_social' => 'Distribuidora ABC Ltda', 'nome_fantasia' => 'ABC Distribuidora', 'cnpj' => '12.345.678/0001-99', 'fone' => '(67)3333-1111', 'email' => 'contato@abc.com.br', 'endereco' => 'Av. Principal, 100'],
            ['razao_social' => 'Comércio XYZ S/A',       'nome_fantasia' => 'XYZ Comércio',      'cnpj' => '98.765.432/0001-11', 'fone' => '(67)3333-2222', 'email' => 'vendas@xyz.com.br',  'endereco' => 'Rua Central, 200'],
        ]);

        DB::table('cliente')->insert([
            ['nome' => 'Anderson Silva', 'endereco' => 'Rua Rui Barbosa, 123', 'telefone' => '(67)9999-8888', 'email' => 'anderson@gmail.com', 'foto' => ''],
            ['nome' => 'Bianca Souza',   'endereco' => 'Av. Afonso Pena, 456', 'telefone' => '(67)9988-7766', 'email' => 'bianca@gmail.com',   'foto' => ''],
            ['nome' => 'Carlos Alberto', 'endereco' => 'Rua Ceará, 333',       'telefone' => '(67)9977-5543', 'email' => 'carlos@gmail.com',   'foto' => ''],
        ]);

        DB::table('produto')->insert([
            ['nome' => 'Arroz 5kg',          'embalagem' => 'Pacote',  'codigo_barra' => '7891000000011', 'qtde_minima' => 20, 'qtde_maxima' => 200, 'quantidade_estoque' => 100, 'valor_compra' => 18.50, 'valor_venda' => 29.90, 'categoria_id' => 1],
            ['nome' => 'Feijão 1kg',         'embalagem' => 'Pacote',  'codigo_barra' => '7891000000028', 'qtde_minima' => 20, 'qtde_maxima' => 150, 'quantidade_estoque' => 80,  'valor_compra' => 7.80,  'valor_venda' => 12.90, 'categoria_id' => 1],
            ['nome' => 'Refrigerante 2L',    'embalagem' => 'Garrafa', 'codigo_barra' => '7891000000035', 'qtde_minima' => 30, 'qtde_maxima' => 120, 'quantidade_estoque' => 60,  'valor_compra' => 5.00,  'valor_venda' => 8.90,  'categoria_id' => 2],
            ['nome' => 'Detergente 500ml',   'embalagem' => 'Frasco',  'codigo_barra' => '7891000000042', 'qtde_minima' => 15, 'qtde_maxima' => 100, 'quantidade_estoque' => 50,  'valor_compra' => 2.50,  'valor_venda' => 4.90,  'categoria_id' => 3],
            ['nome' => 'Sabonete 90g',       'embalagem' => 'Unidade', 'codigo_barra' => '7891000000059', 'qtde_minima' => 25, 'qtde_maxima' => 200, 'quantidade_estoque' => 10,  'valor_compra' => 1.80,  'valor_venda' => 3.20,  'categoria_id' => 4],
            ['nome' => 'Fone de ouvido',     'embalagem' => 'Caixa',   'codigo_barra' => '7891000000066', 'qtde_minima' => 5,  'qtde_maxima' => 30,  'quantidade_estoque' => 12,  'valor_compra' => 25.00, 'valor_venda' => 49.90, 'categoria_id' => 5],
        ]);

        // Orçamentos de exemplo (não mexem no estoque)
        DB::table('orcamento_compra')->insert(['fornecedor_id' => 1, 'data' => date('Y-m-d'), 'documento' => 'OC-001', 'valor' => 0]);
        DB::table('orcamento_compra_item')->insert([
            ['orcamento_compra_id' => 1, 'produto_id' => 1, 'quantidade' => 10, 'valor' => 18.00],
            ['orcamento_compra_id' => 1, 'produto_id' => 5, 'quantidade' => 50, 'valor' => 1.75],
        ]);
        DB::table('orcamento_compra')->where('id', 1)->update(['valor' => 10 * 18.00 + 50 * 1.75]);

        DB::table('orcamento_venda')->insert(['cliente_id' => 2, 'data' => date('Y-m-d'), 'documento' => 'OV-001', 'valor' => 0]);
        DB::table('orcamento_venda_item')->insert([
            ['orcamento_venda_id' => 1, 'produto_id' => 3, 'quantidade' => 6, 'valor' => 8.90],
            ['orcamento_venda_id' => 1, 'produto_id' => 6, 'quantidade' => 1, 'valor' => 49.90],
        ]);
        DB::table('orcamento_venda')->where('id', 1)->update(['valor' => 6 * 8.90 + 1 * 49.90]);
    }
}

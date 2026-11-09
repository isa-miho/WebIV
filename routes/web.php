<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\OrcamentoCompraController;
use App\Http\Controllers\OrcamentoVendaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;

Route::get('/', function () { return view('index'); });

// ===================== Cadastros =====================

// Categoria
Route::get('/categoria/listar',       [CategoriaController::class, 'listar']);
Route::get('/categoria/novo',         [CategoriaController::class, 'novo']);
Route::post('/categoria/salvar',      [CategoriaController::class, 'salvar']);
Route::get('/categoria/editar/{id}',  [CategoriaController::class, 'editar']);
Route::get('/categoria/excluir/{id}', [CategoriaController::class, 'excluir']);

// Cliente
Route::get('/cliente/listar',       [ClienteController::class, 'listar']);
Route::get('/cliente/novo',         [ClienteController::class, 'novo']);
Route::post('/cliente/salvar',      [ClienteController::class, 'salvar']);
Route::get('/cliente/editar/{id}',  [ClienteController::class, 'editar']);
Route::get('/cliente/excluir/{id}', [ClienteController::class, 'excluir']);

// Fornecedor
Route::get('/fornecedor/listar',       [FornecedorController::class, 'listar']);
Route::get('/fornecedor/novo',         [FornecedorController::class, 'novo']);
Route::post('/fornecedor/salvar',      [FornecedorController::class, 'salvar']);
Route::get('/fornecedor/editar/{id}',  [FornecedorController::class, 'editar']);
Route::get('/fornecedor/excluir/{id}', [FornecedorController::class, 'excluir']);

// Produto
Route::get('/produto/listar',       [ProdutoController::class, 'listar']);
Route::get('/produto/relatorio',    [ProdutoController::class, 'relatorio']);
Route::get('/produto/novo',         [ProdutoController::class, 'novo']);
Route::post('/produto/salvar',      [ProdutoController::class, 'salvar']);
Route::get('/produto/editar/{id}',  [ProdutoController::class, 'editar']);
Route::get('/produto/excluir/{id}', [ProdutoController::class, 'excluir']);

// ===================== Compras =====================

// Orçamento de Compra
Route::get('/orcamento_compra/listar',            [OrcamentoCompraController::class, 'listar']);
Route::get('/orcamento_compra/novo',              [OrcamentoCompraController::class, 'novo']);
Route::post('/orcamento_compra/salvar',           [OrcamentoCompraController::class, 'salvar']);
Route::get('/orcamento_compra/editar/{id}',       [OrcamentoCompraController::class, 'editar']);
Route::get('/orcamento_compra/excluir/{id}',      [OrcamentoCompraController::class, 'excluir']);
Route::get('/orcamento_compra/itens/{id}',        [OrcamentoCompraController::class, 'itens']);
Route::post('/orcamento_compra/itens/salvar',     [OrcamentoCompraController::class, 'salvarItem']);
Route::get('/orcamento_compra/item/editar/{id}',  [OrcamentoCompraController::class, 'editarItem']);
Route::get('/orcamento_compra/item/excluir/{id}', [OrcamentoCompraController::class, 'excluirItem']);
Route::get('/orcamento_compra/gerar_compra/{id}', [OrcamentoCompraController::class, 'gerarCompra']);

// Compra
Route::get('/compra/listar',            [CompraController::class, 'listar']);
Route::get('/compra/novo',              [CompraController::class, 'novo']);
Route::post('/compra/salvar',           [CompraController::class, 'salvar']);
Route::get('/compra/editar/{id}',       [CompraController::class, 'editar']);
Route::get('/compra/excluir/{id}',      [CompraController::class, 'excluir']);
Route::get('/compra/itens/{id}',        [CompraController::class, 'itens']);
Route::post('/compra/itens/salvar',     [CompraController::class, 'salvarItem']);
Route::get('/compra/item/editar/{id}',  [CompraController::class, 'editarItem']);
Route::get('/compra/item/excluir/{id}', [CompraController::class, 'excluirItem']);

// ===================== Vendas =====================

// Orçamento de Venda
Route::get('/orcamento_venda/listar',            [OrcamentoVendaController::class, 'listar']);
Route::get('/orcamento_venda/novo',              [OrcamentoVendaController::class, 'novo']);
Route::post('/orcamento_venda/salvar',           [OrcamentoVendaController::class, 'salvar']);
Route::get('/orcamento_venda/editar/{id}',       [OrcamentoVendaController::class, 'editar']);
Route::get('/orcamento_venda/excluir/{id}',      [OrcamentoVendaController::class, 'excluir']);
Route::get('/orcamento_venda/itens/{id}',        [OrcamentoVendaController::class, 'itens']);
Route::post('/orcamento_venda/itens/salvar',     [OrcamentoVendaController::class, 'salvarItem']);
Route::get('/orcamento_venda/item/editar/{id}',  [OrcamentoVendaController::class, 'editarItem']);
Route::get('/orcamento_venda/item/excluir/{id}', [OrcamentoVendaController::class, 'excluirItem']);
Route::get('/orcamento_venda/gerar_venda/{id}',  [OrcamentoVendaController::class, 'gerarVenda']);

// Venda
Route::get('/venda/listar',            [VendaController::class, 'listar']);
Route::get('/venda/novo',              [VendaController::class, 'novo']);
Route::post('/venda/salvar',           [VendaController::class, 'salvar']);
Route::get('/venda/editar/{id}',       [VendaController::class, 'editar']);
Route::get('/venda/excluir/{id}',      [VendaController::class, 'excluir']);
Route::get('/venda/itens/{id}',        [VendaController::class, 'itens']);
Route::post('/venda/itens/salvar',     [VendaController::class, 'salvarItem']);
Route::get('/venda/item/editar/{id}',  [VendaController::class, 'editarItem']);
Route::get('/venda/item/excluir/{id}', [VendaController::class, 'excluirItem']);
Route::get('/venda/pdf/{id}',          [VendaController::class, 'pdf']);

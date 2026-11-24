<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ContaPagarController;
use App\Http\Controllers\ContaReceberController;
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// depois do login o Breeze manda para /dashboard: aqui ele vai para a página inicial
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Tudo abaixo exige login (Breeze)
Route::middleware('auth')->group(function () {

    // Perfil do usuário (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===================== Cadastros =====================

    // Categoria
    Route::get('/categoria/listar',       [CategoriaController::class, 'listar']);
    Route::get('/categoria/novo',         [CategoriaController::class, 'novo']);
    Route::post('/categoria/salvar',      [CategoriaController::class, 'salvar']);
    Route::get('/categoria/editar/{id}',  [CategoriaController::class, 'editar']);
    Route::get('/categoria/excluir/{id}', [CategoriaController::class, 'excluir']);
    Route::get('/categoria/margem/{id}',  [CategoriaController::class, 'margem']);
    Route::post('/categoria/margem',      [CategoriaController::class, 'salvarMargem']);

    // Produto
    Route::get('/produto/listar',       [ProdutoController::class, 'listar']);
    Route::get('/produto/relatorio',    [ProdutoController::class, 'relatorio']);
    Route::get('/produto/novo',         [ProdutoController::class, 'novo']);
    Route::post('/produto/salvar',      [ProdutoController::class, 'salvar']);
    Route::get('/produto/editar/{id}',  [ProdutoController::class, 'editar']);
    Route::get('/produto/excluir/{id}', [ProdutoController::class, 'excluir']);

    // Fornecedor
    Route::get('/fornecedor/listar',       [FornecedorController::class, 'listar']);
    Route::get('/fornecedor/novo',         [FornecedorController::class, 'novo']);
    Route::post('/fornecedor/salvar',      [FornecedorController::class, 'salvar']);
    Route::get('/fornecedor/editar/{id}',  [FornecedorController::class, 'editar']);
    Route::get('/fornecedor/excluir/{id}', [FornecedorController::class, 'excluir']);

    // Cliente
    Route::get('/cliente/listar',       [ClienteController::class, 'listar']);
    Route::get('/cliente/novo',         [ClienteController::class, 'novo']);
    Route::post('/cliente/salvar',      [ClienteController::class, 'salvar']);
    Route::get('/cliente/editar/{id}',  [ClienteController::class, 'editar']);
    Route::get('/cliente/excluir/{id}', [ClienteController::class, 'excluir']);

    // Forma de pagamento (com as parcelas)
    Route::get('/forma_pagamento/listar',            [FormaPagamentoController::class, 'listar']);
    Route::get('/forma_pagamento/novo',              [FormaPagamentoController::class, 'novo']);
    Route::post('/forma_pagamento/salvar',           [FormaPagamentoController::class, 'salvar']);
    Route::get('/forma_pagamento/editar/{id}',       [FormaPagamentoController::class, 'editar']);
    Route::get('/forma_pagamento/excluir/{id}',      [FormaPagamentoController::class, 'excluir']);
    Route::get('/forma_pagamento/itens/{id}',        [FormaPagamentoController::class, 'itens']);
    Route::post('/forma_pagamento/itens/salvar',     [FormaPagamentoController::class, 'salvarItem']);
    Route::get('/forma_pagamento/item/editar/{id}',  [FormaPagamentoController::class, 'editarItem']);
    Route::get('/forma_pagamento/item/excluir/{id}', [FormaPagamentoController::class, 'excluirItem']);
    Route::get('/forma_pagamento/gerar/{id}',        [FormaPagamentoController::class, 'gerarParcelas']);

    // ===================== Compras =====================

    Route::get('/compra/listar',            [CompraController::class, 'listar']);
    Route::get('/compra/novo',              [CompraController::class, 'novo']);
    Route::post('/compra/salvar',           [CompraController::class, 'salvar']);
    Route::get('/compra/editar/{id}',       [CompraController::class, 'editar']);
    Route::get('/compra/excluir/{id}',      [CompraController::class, 'excluir']);
    Route::get('/compra/itens/{id}',        [CompraController::class, 'itens']);
    Route::post('/compra/itens/salvar',     [CompraController::class, 'salvarItem']);
    Route::get('/compra/item/editar/{id}',  [CompraController::class, 'editarItem']);
    Route::get('/compra/item/excluir/{id}', [CompraController::class, 'excluirItem']);

    Route::get('/contas_pagar/listar',        [ContaPagarController::class, 'listar']);
    Route::get('/contas_pagar/baixa/{id}',    [ContaPagarController::class, 'baixa']);
    Route::post('/contas_pagar/baixa',        [ContaPagarController::class, 'salvarBaixa']);
    Route::get('/contas_pagar/estornar/{id}', [ContaPagarController::class, 'estornar']);

    // ===================== Vendas =====================

    Route::get('/venda/listar',            [VendaController::class, 'listar']);
    Route::get('/venda/novo',              [VendaController::class, 'novo']);
    Route::post('/venda/salvar',           [VendaController::class, 'salvar']);
    Route::get('/venda/editar/{id}',       [VendaController::class, 'editar']);
    Route::get('/venda/excluir/{id}',      [VendaController::class, 'excluir']);
    Route::get('/venda/itens/{id}',        [VendaController::class, 'itens']);
    Route::post('/venda/itens/salvar',     [VendaController::class, 'salvarItem']);
    Route::get('/venda/item/editar/{id}',  [VendaController::class, 'editarItem']);
    Route::get('/venda/item/excluir/{id}', [VendaController::class, 'excluirItem']);
    Route::get('/venda/finalizar/{id}',    [VendaController::class, 'finalizar']);
    Route::get('/venda/pdf/{id}',          [VendaController::class, 'pdf']);

    Route::get('/contas_receber/listar',        [ContaReceberController::class, 'listar']);
    Route::get('/contas_receber/baixa/{id}',    [ContaReceberController::class, 'baixa']);
    Route::post('/contas_receber/baixa',        [ContaReceberController::class, 'salvarBaixa']);
    Route::get('/contas_receber/estornar/{id}', [ContaReceberController::class, 'estornar']);
    Route::get('/contas_receber/relatorio',     [ContaReceberController::class, 'relatorio']);
    Route::get('/contas_receber/relatorio/pdf', [ContaReceberController::class, 'relatorioPdf']);
});

require __DIR__.'/auth.php';

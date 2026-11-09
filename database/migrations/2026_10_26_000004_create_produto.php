<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produto', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('embalagem')->nullable();
            $table->string('codigo_barra')->nullable();
            $table->integer('qtde_minima')->default(0);
            $table->integer('qtde_maxima')->default(0);
            $table->integer('quantidade_estoque')->default(0);
            $table->decimal('valor_compra', 10, 2)->default(0);
            $table->decimal('valor_venda', 10, 2)->default(0);
            // se a categoria for excluída, o produto fica sem categoria
            $table->foreignId('categoria_id')->nullable()->constrained('categoria')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produto');
    }
};

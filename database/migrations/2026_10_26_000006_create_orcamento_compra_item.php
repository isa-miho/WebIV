<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orcamento_compra_item', function (Blueprint $table) {
            $table->id();
            // ao excluir o cabeçalho, os itens são excluídos junto
            $table->foreignId('orcamento_compra_id')->constrained('orcamento_compra')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produto');
            $table->integer('quantidade');
            $table->decimal('valor', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orcamento_compra_item');
    }
};

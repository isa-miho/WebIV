<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venda_item', function (Blueprint $table) {
            $table->id();
            // ao excluir o cabeçalho, os itens são excluídos junto
            $table->foreignId('venda_id')->constrained('venda')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produto');
            $table->integer('quantidade');
            $table->decimal('valor', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venda_item');
    }
};

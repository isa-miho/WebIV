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
            $table->foreignId('venda_id')->constrained('venda')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produto');
            $table->integer('quantidade');
            $table->decimal('valor_unitario', 10, 2);
            $table->decimal('valor_total', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venda_item');
    }
};

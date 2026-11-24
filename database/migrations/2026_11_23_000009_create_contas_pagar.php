<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contas_pagar', function (Blueprint $table) {
            $table->id();
            $table->string('documento');
            $table->foreignId('fornecedor_id')->constrained('fornecedor');
            $table->foreignId('compra_id')->constrained('compra')->cascadeOnDelete();
            $table->date('data_vencimento');
            $table->decimal('valor', 10, 2);
            $table->date('data_pagamento')->nullable();
            $table->string('situacao')->default('aberta');   // aberta ou paga
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas_pagar');
    }
};

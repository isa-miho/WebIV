<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contas_receber', function (Blueprint $table) {
            $table->id();
            $table->string('documento');
            $table->foreignId('cliente_id')->constrained('cliente');
            $table->foreignId('venda_id')->constrained('venda')->cascadeOnDelete();
            $table->date('data_vencimento');
            $table->decimal('valor', 10, 2);
            $table->date('data_pagamento')->nullable();   // vazio = não pago
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas_receber');
    }
};

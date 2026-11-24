<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venda', function (Blueprint $table) {
            $table->id();
            $table->string('documento');
            $table->foreignId('cliente_id')->constrained('cliente');
            $table->date('data');
            $table->foreignId('forma_pagamento_id')->constrained('forma_pagamento');
            $table->decimal('valor', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venda');
    }
};

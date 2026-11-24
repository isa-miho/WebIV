<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedor', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('documento');   // CNPJ ou CPF
            $table->string('email')->nullable();
            $table->string('endereco')->nullable();
            $table->string('telefone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedor');
    }
};

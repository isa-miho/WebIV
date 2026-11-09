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
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            $table->string('endereco')->nullable();
            $table->string('fone')->nullable();
            $table->string('email')->nullable();
            $table->string('cnpj')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedor');
    }
};

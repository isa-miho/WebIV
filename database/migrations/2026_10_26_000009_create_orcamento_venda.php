<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orcamento_venda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('cliente');
            $table->date('data');
            $table->string('documento');
            $table->decimal('valor', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orcamento_venda');
    }
};

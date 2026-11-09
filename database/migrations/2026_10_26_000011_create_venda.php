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
            $table->foreignId('cliente_id')->constrained('cliente');
            $table->date('data');
            $table->string('documento');
            $table->decimal('valor', 10, 2)->default(0);
            $table->foreignId('orcamento_venda_id')->nullable()->constrained('orcamento_venda')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venda');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedor');
            $table->date('data');
            $table->string('documento');
            $table->decimal('valor', 10, 2)->default(0);
            $table->foreignId('orcamento_compra_id')->nullable()->constrained('orcamento_compra')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compra');
    }
};

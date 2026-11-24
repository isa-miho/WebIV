<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forma_pagamento_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forma_pagamento_id')->constrained('forma_pagamento')->cascadeOnDelete();
            $table->decimal('porcentagem', 5, 2);   // % do valor total nesta parcela
            $table->integer('dia');                 // dias após a data da compra/venda
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forma_pagamento_item');
    }
};

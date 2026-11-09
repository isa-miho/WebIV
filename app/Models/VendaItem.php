<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendaItem extends Model
{
    protected $table = 'venda_item';
    public $timestamps = false;

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function cabecalho(): BelongsTo
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    // quantidade x valor unitário
    public function subtotal(): float
    {
        return $this->quantidade * $this->valor;
    }
}

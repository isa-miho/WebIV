<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompraItem extends Model
{
    protected $table = 'compra_item';
    public $timestamps = false;

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function cabecalho(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    // quantidade x valor unitário
    public function subtotal(): float
    {
        return $this->quantidade * $this->valor;
    }
}

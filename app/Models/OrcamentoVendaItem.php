<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrcamentoVendaItem extends Model
{
    protected $table = 'orcamento_venda_item';
    public $timestamps = false;

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function cabecalho(): BelongsTo
    {
        return $this->belongsTo(OrcamentoVenda::class, 'orcamento_venda_id');
    }

    // quantidade x valor unitário
    public function subtotal(): float
    {
        return $this->quantidade * $this->valor;
    }
}

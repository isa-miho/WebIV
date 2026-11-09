<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrcamentoVenda extends Model
{
    protected $table = 'orcamento_venda';
    public $timestamps = false;

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(OrcamentoVendaItem::class, 'orcamento_venda_id');
    }

    protected function casts(): array
    {
        return [
            'data' => 'date',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    protected $table = 'venda';
    public $timestamps = false;

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(VendaItem::class, 'venda_id');
    }

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(OrcamentoVenda::class, 'orcamento_venda_id');
    }

    protected function casts(): array
    {
        return [
            'data' => 'date',
        ];
    }
}

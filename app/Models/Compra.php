<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
{
    protected $table = 'compra';
    public $timestamps = false;

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(CompraItem::class, 'compra_id');
    }

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(OrcamentoCompra::class, 'orcamento_compra_id');
    }

    protected function casts(): array
    {
        return [
            'data' => 'date',
        ];
    }
}

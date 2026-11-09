<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrcamentoCompra extends Model
{
    protected $table = 'orcamento_compra';
    public $timestamps = false;

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(OrcamentoCompraItem::class, 'orcamento_compra_id');
    }

    protected function casts(): array
    {
        return [
            'data' => 'date',
        ];
    }
}

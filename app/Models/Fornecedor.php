<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fornecedor extends Model
{
    protected $table = 'fornecedor';
    public $timestamps = false;

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    public function orcamentosCompra(): HasMany
    {
        return $this->hasMany(OrcamentoCompra::class);
    }
}

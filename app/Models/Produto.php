<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produto extends Model
{
    protected $table = 'produto';
    public $timestamps = false;

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // true quando o estoque está abaixo do mínimo
    public function estoqueBaixo(): bool
    {
        return $this->qtde_minima > 0 && $this->quantidade_estoque < $this->qtde_minima;
    }
}

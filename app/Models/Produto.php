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

    // valor de venda = valor de compra + margem de lucro (%)
    public static function calcularValorVenda($valorCompra, $margem): float
    {
        return round($valorCompra * (1 + $margem / 100), 2);
    }

    // Atualiza o valor de venda pela margem da categoria do produto
    public function atualizarValorVenda(): void
    {
        $margem = $this->categoria ? $this->categoria->margem_lucro : 0;
        $this->valor_venda = self::calcularValorVenda($this->valor_compra, $margem);
    }
}

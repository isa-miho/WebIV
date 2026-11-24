<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categoria';
    public $timestamps = false;

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }

    // Recalcula o valor de venda de todos os produtos desta categoria
    // conforme a margem de lucro atual. Retorna quantos produtos foram alterados.
    public function atualizarPrecosDosProdutos(): int
    {
        $produtos = $this->produtos()->get();
        foreach ($produtos as $produto) {
            $produto->valor_venda = Produto::calcularValorVenda($produto->valor_compra, $this->margem_lucro);
            $produto->save();
        }
        return $produtos->count();
    }
}

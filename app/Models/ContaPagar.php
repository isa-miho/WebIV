<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContaPagar extends Model
{
    protected $table = 'contas_pagar';
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'data_vencimento' => 'date',
            'data_pagamento' => 'date',
        ];
    }

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    public function paga(): bool
    {
        return $this->situacao == 'paga';
    }

    // Paga, Vencida ou A vencer
    public function status(): string
    {
        if ($this->paga()) {
            return 'Paga';
        }
        return $this->data_vencimento->lt(today()) ? 'Vencida' : 'A vencer';
    }
}

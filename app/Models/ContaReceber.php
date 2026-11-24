<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContaReceber extends Model
{
    protected $table = 'contas_receber';
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'data_vencimento' => 'date',
            'data_pagamento' => 'date',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class);
    }

    public function paga(): bool
    {
        return $this->data_pagamento != null;
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

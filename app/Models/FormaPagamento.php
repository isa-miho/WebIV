<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormaPagamento extends Model
{
    protected $table = 'forma_pagamento';
    public $timestamps = false;

    public function itens(): HasMany
    {
        return $this->hasMany(FormaPagamentoItem::class)->orderBy('dia');
    }

    public function totalPorcentagem(): float
    {
        return round($this->itens->sum('porcentagem'), 2);
    }

    // A forma só pode ser usada quando tem todas as parcelas e elas somam 100%
    public function completa(): bool
    {
        return $this->itens->count() == $this->numero_parcelas
            && abs($this->totalPorcentagem() - 100) < 0.01;
    }

    // Divide o valor total nas parcelas desta forma de pagamento.
    // Ex.: R$ 100,00 em "30/60 dias" (50% + 50%) a partir de 01/12
    //      -> [ 31/12: R$ 50,00 ; 30/01: R$ 50,00 ]
    public function calcularParcelas($valorTotal, $data): array
    {
        $parcelas = [];
        $acumulado = 0;
        $quantidade = $this->itens->count();

        foreach ($this->itens as $i => $item) {
            if ($i == $quantidade - 1) {
                // a última parcela recebe a diferença dos arredondamentos
                $valor = round($valorTotal - $acumulado, 2);
            } else {
                $valor = round($valorTotal * $item->porcentagem / 100, 2);
            }
            $acumulado += $valor;

            $parcelas[] = [
                'numero' => $i + 1,
                'vencimento' => Carbon::parse($data)->addDays($item->dia),
                'valor' => $valor,
            ];
        }
        return $parcelas;
    }
}

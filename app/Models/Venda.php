<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    protected $table = 'venda';
    public $timestamps = false;

    protected function casts(): array
    {
        return ['data' => 'date'];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(VendaItem::class);
    }

    public function contas(): HasMany
    {
        return $this->hasMany(ContaReceber::class)->orderBy('data_vencimento');
    }

    // Depois que alguma parcela foi recebida, a venda não pode mais ser alterada
    public function temParcelaPaga(): bool
    {
        return $this->contas()->whereNotNull('data_pagamento')->exists();
    }

    public function atualizarTotal(): void
    {
        $this->valor = $this->itens()->sum('valor_total');
        $this->save();
    }

    // Apaga as contas a receber desta venda e gera de novo conforme o parcelamento
    public function gerarContasReceber(): void
    {
        ContaReceber::where('venda_id', $this->id)->delete();
        if ($this->valor <= 0) {
            return;
        }

        $forma = $this->formaPagamento()->with('itens')->first();
        $parcelas = $forma->calcularParcelas($this->valor, $this->data);
        $total = count($parcelas);

        foreach ($parcelas as $parcela) {
            $conta = new ContaReceber();
            $conta->documento = "{$this->documento} - {$parcela['numero']}/{$total}";
            $conta->cliente_id = $this->cliente_id;
            $conta->venda_id = $this->id;
            $conta->data_vencimento = $parcela['vencimento'];
            $conta->valor = $parcela['valor'];
            $conta->save();
        }
    }
}

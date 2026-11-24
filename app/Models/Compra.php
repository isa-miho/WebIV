<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
{
    protected $table = 'compra';
    public $timestamps = false;

    protected function casts(): array
    {
        return ['data' => 'date'];
    }

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(CompraItem::class);
    }

    public function contas(): HasMany
    {
        return $this->hasMany(ContaPagar::class)->orderBy('data_vencimento');
    }

    // Depois que alguma parcela foi paga, a compra não pode mais ser alterada
    public function temParcelaPaga(): bool
    {
        return $this->contas()->where('situacao', 'paga')->exists();
    }

    // Soma os itens e grava o valor total da compra
    public function atualizarTotal(): void
    {
        $this->valor = $this->itens()->sum('valor_total');
        $this->save();
    }

    // Apaga as contas a pagar desta compra e gera de novo conforme o parcelamento
    public function gerarContasPagar(): void
    {
        ContaPagar::where('compra_id', $this->id)->delete();
        if ($this->valor <= 0) {
            return;
        }

        $forma = $this->formaPagamento()->with('itens')->first();
        $parcelas = $forma->calcularParcelas($this->valor, $this->data);
        $total = count($parcelas);

        foreach ($parcelas as $parcela) {
            $conta = new ContaPagar();
            $conta->documento = "{$this->documento} - {$parcela['numero']}/{$total}";
            $conta->fornecedor_id = $this->fornecedor_id;
            $conta->compra_id = $this->id;
            $conta->data_vencimento = $parcela['vencimento'];
            $conta->valor = $parcela['valor'];
            $conta->situacao = 'aberta';
            $conta->save();
        }
    }
}

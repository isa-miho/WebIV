<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemOrcamentoCompra extends Model
{
    protected $table = 'item_orcamento_compra';
    public $timestamps = false;

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function orcamentoCompra(): BelongsTo
    {
        return $this->belongsTo(OrcamentoCompra::class, 'orcamento_compra_id');
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Usado nos itens do orçamento de compra e da compra
class ItemCompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produto_id' => ['required', 'exists:App\Models\Produto,id'],
            'quantidade' => ['required', 'integer', 'min:1'],
            'valor'      => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'produto_id.required' => 'Selecione um produto',
            'produto_id.exists'   => 'Produto inválido',
            'quantidade.required' => 'A Quantidade é obrigatória',
            'quantidade.integer'  => 'A Quantidade deve ser um número inteiro',
            'quantidade.min'      => 'A Quantidade deve ser pelo menos 1',
            'valor.required'      => 'O Valor unitário é obrigatório',
            'valor.min'           => 'O Valor unitário deve ser maior que zero',
        ];
    }
}

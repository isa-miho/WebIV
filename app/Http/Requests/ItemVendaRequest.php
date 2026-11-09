<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Usado nos itens do orçamento de venda e da venda
// (o valor vem do cadastro do produto, por isso não é validado aqui)
class ItemVendaRequest extends FormRequest
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
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descricao'          => ['required', 'min:3'],
            'valor_compra'       => ['required', 'numeric', 'min:0'],
            'quantidade_estoque' => ['required', 'integer', 'min:0'],
            'categoria_id'       => ['required', 'exists:App\Models\Categoria,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required'          => 'A Descrição é obrigatória',
            'descricao.min'               => 'A Descrição deve ter pelo menos 3 caracteres',
            'valor_compra.required'       => 'O Valor de compra é obrigatório',
            'valor_compra.numeric'        => 'O Valor de compra deve ser numérico',
            'valor_compra.min'            => 'O Valor de compra não pode ser negativo',
            'quantidade_estoque.required' => 'A Quantidade em estoque é obrigatória',
            'quantidade_estoque.integer'  => 'A Quantidade em estoque deve ser um número inteiro',
            'quantidade_estoque.min'      => 'A Quantidade em estoque não pode ser negativa',
            'categoria_id.required'       => 'A Categoria é obrigatória',
            'categoria_id.exists'         => 'Categoria inválida',
        ];
    }
}

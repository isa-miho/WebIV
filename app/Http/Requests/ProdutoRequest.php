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
            'nome'               => ['required', 'min:3'],
            'valor_compra'       => ['required', 'numeric', 'min:0'],
            'valor_venda'        => ['required', 'numeric', 'min:0'],
            'quantidade_estoque' => ['required', 'integer', 'min:0'],
            'qtde_minima'        => ['nullable', 'integer', 'min:0'],
            'qtde_maxima'        => ['nullable', 'integer', 'min:0'],
            'categoria_id'       => ['required', 'exists:App\Models\Categoria,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'               => 'O Nome é obrigatório',
            'nome.min'                    => 'O Nome deve ter pelo menos 3 caracteres',
            'valor_compra.required'       => 'O Valor de Compra é obrigatório',
            'valor_compra.numeric'        => 'O Valor de Compra deve ser numérico',
            'valor_venda.required'        => 'O Valor de Venda é obrigatório',
            'valor_venda.numeric'         => 'O Valor de Venda deve ser numérico',
            'quantidade_estoque.required' => 'A Quantidade em Estoque é obrigatória',
            'quantidade_estoque.integer'  => 'A Quantidade deve ser um número inteiro',
            'qtde_minima.integer'         => 'A Quantidade mínima deve ser um número inteiro',
            'qtde_maxima.integer'         => 'A Quantidade máxima deve ser um número inteiro',
            'categoria_id.required'       => 'A Categoria é obrigatória',
            'categoria_id.exists'         => 'Categoria inválida',
        ];
    }
}

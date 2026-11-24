<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descricao'    => ['required', 'min:3'],
            'margem_lucro' => ['required', 'numeric', 'min:0', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required'    => 'A Descrição é obrigatória',
            'descricao.min'         => 'A Descrição deve ter pelo menos 3 caracteres',
            'margem_lucro.required' => 'A Margem de lucro é obrigatória',
            'margem_lucro.numeric'  => 'A Margem de lucro deve ser um número',
            'margem_lucro.min'      => 'A Margem de lucro não pode ser negativa',
            'margem_lucro.max'      => 'A Margem de lucro deve ser no máximo 1000%',
        ];
    }
}

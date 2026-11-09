<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descricao'    => ['required', 'min:5'],
            'margem_lucro' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required'   => 'A Descrição é obrigatória',
            'descricao.min'        => 'A Descrição deve conter pelo menos 5 caracteres',
            'margem_lucro.numeric' => 'A Margem de lucro deve ser um número',
            'margem_lucro.min'     => 'A Margem de lucro não pode ser negativa',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormaPagamentoItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'porcentagem' => ['required', 'numeric', 'min:0.01', 'max:100'],
            'dia'         => ['required', 'integer', 'min:0', 'max:3650'],
        ];
    }

    public function messages(): array
    {
        return [
            'porcentagem.required' => 'A Porcentagem é obrigatória',
            'porcentagem.numeric'  => 'A Porcentagem deve ser um número',
            'porcentagem.min'      => 'A Porcentagem deve ser maior que zero',
            'porcentagem.max'      => 'A Porcentagem deve ser no máximo 100',
            'dia.required'         => 'O Dia é obrigatório',
            'dia.integer'          => 'O Dia deve ser um número inteiro',
            'dia.min'              => 'O Dia não pode ser negativo',
        ];
    }
}

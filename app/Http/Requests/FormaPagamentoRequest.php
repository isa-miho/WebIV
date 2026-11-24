<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormaPagamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descricao'       => ['required', 'min:3'],
            'numero_parcelas' => ['required', 'integer', 'min:1', 'max:24'],
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required'       => 'A Descrição é obrigatória',
            'descricao.min'            => 'A Descrição deve ter pelo menos 3 caracteres',
            'numero_parcelas.required' => 'O Número de parcelas é obrigatório',
            'numero_parcelas.integer'  => 'O Número de parcelas deve ser um número inteiro',
            'numero_parcelas.min'      => 'Deve haver pelo menos 1 parcela',
            'numero_parcelas.max'      => 'O máximo é 24 parcelas',
        ];
    }
}

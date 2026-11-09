<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fornecedor_id' => ['required', 'exists:App\Models\Fornecedor,id'],
            'data'      => ['required', 'date'],
            'documento' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'fornecedor_id.required'  => 'O Fornecedor é obrigatório',
            'fornecedor_id.exists'    => 'Fornecedor inválido',
            'data.required'      => 'A Data é obrigatória',
            'data.date'          => 'Data inválida',
            'documento.required' => 'O Documento é obrigatório',
        ];
    }
}

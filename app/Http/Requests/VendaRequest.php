<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'exists:App\Models\Cliente,id'],
            'data'      => ['required', 'date'],
            'documento' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required'  => 'O Cliente é obrigatório',
            'cliente_id.exists'    => 'Cliente inválido',
            'data.required'      => 'A Data é obrigatória',
            'data.date'          => 'Data inválida',
            'documento.required' => 'O Documento é obrigatório',
        ];
    }
}

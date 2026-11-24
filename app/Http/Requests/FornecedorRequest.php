<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FornecedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'      => ['required', 'min:3'],
            'documento' => ['required', 'min:11'],
            'email'     => ['nullable', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'      => 'O Nome é obrigatório',
            'nome.min'           => 'O Nome deve ter pelo menos 3 caracteres',
            'documento.required' => 'O Documento (CNPJ ou CPF) é obrigatório',
            'documento.min'      => 'O Documento deve ter pelo menos 11 caracteres',
            'email.email'        => 'E-mail inválido',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
            'email'     => ['required', 'email'],
            'arquivo'   => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'      => 'O Nome é obrigatório',
            'nome.min'           => 'O Nome deve ter pelo menos 3 caracteres',
            'documento.required' => 'O Documento (CPF ou CNPJ) é obrigatório',
            'documento.min'      => 'O Documento deve ter pelo menos 11 caracteres',
            'email.required'     => 'O E-mail é obrigatório (a venda é enviada por e-mail)',
            'email.email'        => 'E-mail inválido',
            'arquivo.image'      => 'A foto deve ser uma imagem (jpg, png...)',
            'arquivo.max'        => 'A foto deve ter no máximo 2 MB',
        ];
    }
}

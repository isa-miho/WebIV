<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nome'  => ['required'],
            'email' => ['required', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'  => 'Nome é obrigatório',
            'email.required' => 'E-mail é obrigatório',
            'email.email'    => 'E-mail inválido',
        ];
    }
}

<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class FornecedorRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'razao_social'  => ['required', 'min:3'],
            'cnpj'          => ['required'],
            'email'         => ['required', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'razao_social.required' => 'A Razão Social é obrigatória',
            'razao_social.min'      => 'A Razão Social deve ter pelo menos 3 caracteres',
            'cnpj.required'         => 'O CNPJ é obrigatório',
            'email.required'        => 'O E-mail é obrigatório',
            'email.email'           => 'E-mail inválido',
        ];
    }
}

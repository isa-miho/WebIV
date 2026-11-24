<?php

namespace App\Http\Requests;

use App\Models\FormaPagamento;
use Closure;
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
            'documento'          => ['required'],
            'cliente_id'      => ['required', 'exists:App\Models\Cliente,id'],
            'data'               => ['required', 'date'],
            'forma_pagamento_id' => ['required', 'exists:App\Models\FormaPagamento,id',
                // as parcelas da forma de pagamento precisam somar 100%
                function (string $attribute, mixed $value, Closure $fail) {
                    $forma = FormaPagamento::with('itens')->find($value);
                    if ($forma && !$forma->completa()) {
                        $fail("A forma de pagamento \"{$forma->descricao}\" está incompleta: cadastre as parcelas até somar 100%.");
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'documento.required'          => 'O Documento é obrigatório',
            'cliente_id.required'      => 'O Cliente é obrigatório',
            'cliente_id.exists'        => 'Cliente inválido',
            'data.required'               => 'A Data é obrigatória',
            'data.date'                   => 'Data inválida',
            'forma_pagamento_id.required' => 'A Forma de pagamento é obrigatória',
            'forma_pagamento_id.exists'   => 'Forma de pagamento inválida',
        ];
    }
}

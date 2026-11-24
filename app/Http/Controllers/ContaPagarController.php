<?php

namespace App\Http\Controllers;

use App\Models\ContaPagar;
use Illuminate\Http\Request;

class ContaPagarController extends Controller
{
    function listar(Request $request) {
        $situacao = $request->get('situacao', 'abertas');

        $query = ContaPagar::with(['fornecedor', 'compra'])->orderBy('data_vencimento')->orderBy('id');
        if ($situacao == 'abertas') {
            $query->where('situacao', 'aberta');
        } elseif ($situacao == 'vencidas') {
            $query->where('situacao', 'aberta')->whereDate('data_vencimento', '<', today());
        } elseif ($situacao == 'pagas') {
            $query->where('situacao', 'paga');
        }
        $contas = $query->paginate(10)->withQueryString();

        // totais para os cartões do topo
        $totais = [
            'vencidas' => ContaPagar::query()->where('situacao', 'aberta')->whereDate('data_vencimento', '<', today())->sum('valor'),
            'a_vencer' => ContaPagar::query()->where('situacao', 'aberta')->whereDate('data_vencimento', '>=', today())->sum('valor'),
            'pagas'    => ContaPagar::query()->where('situacao', 'paga')->sum('valor'),
        ];

        return view('listagem_contas_pagar', compact('contas', 'situacao', 'totais'));
    }

    // Tela para informar a data do pagamento
    function baixa($id) {
        $conta = ContaPagar::with(['fornecedor', 'compra'])->find($id);
        return view('baixa_contas_pagar', compact('conta'));
    }

    function salvarBaixa(Request $request) {
        $request->validate(
            ['data_pagamento' => ['required', 'date', 'before_or_equal:today']],
            [
                'data_pagamento.required'        => 'Informe a data do pagamento',
                'data_pagamento.date'            => 'Data inválida',
                'data_pagamento.before_or_equal' => 'A data do pagamento não pode ser no futuro',
            ]
        );

        $conta = ContaPagar::find($request->id);
        $conta->data_pagamento = $request->data_pagamento;
        $conta->situacao = 'paga';
        $conta->save();

        return redirect('contas_pagar/listar')->with('mensagem', "Baixa da conta {$conta->documento} registrada.");
    }

    // Desfaz a baixa (a conta volta a ficar em aberto)
    function estornar($id) {
        $conta = ContaPagar::find($id);
        $conta->data_pagamento = null;
        $conta->situacao = 'aberta';
        $conta->save();
        return redirect('contas_pagar/listar?situacao=pagas')->with('mensagem', "Baixa da conta {$conta->documento} desfeita.");
    }
}

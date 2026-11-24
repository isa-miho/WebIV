<?php

namespace App\Http\Controllers;

use App\Models\ContaReceber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ContaReceberController extends Controller
{
    function listar(Request $request) {
        $situacao = $request->get('situacao', 'abertas');

        $query = ContaReceber::with(['cliente', 'venda'])->orderBy('data_vencimento')->orderBy('id');
        if ($situacao == 'abertas') {
            $query->whereNull('data_pagamento');
        } elseif ($situacao == 'vencidas') {
            $query->whereNull('data_pagamento')->whereDate('data_vencimento', '<', today());
        } elseif ($situacao == 'pagas') {
            $query->whereNotNull('data_pagamento');
        }
        $contas = $query->paginate(10)->withQueryString();

        // totais para os cartões do topo
        $totais = [
            'vencidas' => ContaReceber::query()->whereNull('data_pagamento')->whereDate('data_vencimento', '<', today())->sum('valor'),
            'a_vencer' => ContaReceber::query()->whereNull('data_pagamento')->whereDate('data_vencimento', '>=', today())->sum('valor'),
            'pagas'    => ContaReceber::query()->whereNotNull('data_pagamento')->sum('valor'),
        ];

        return view('listagem_contas_receber', compact('contas', 'situacao', 'totais'));
    }

    // Tela para informar a data do pagamento
    function baixa($id) {
        $conta = ContaReceber::with(['cliente', 'venda'])->find($id);
        return view('baixa_contas_receber', compact('conta'));
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

        $conta = ContaReceber::find($request->id);
        $conta->data_pagamento = $request->data_pagamento;

        $conta->save();

        return redirect('contas_receber/listar')->with('mensagem', "Baixa da conta {$conta->documento} registrada.");
    }

    // Desfaz a baixa (a conta volta a ficar em aberto)
    function estornar($id) {
        $conta = ContaReceber::find($id);
        $conta->data_pagamento = null;

        $conta->save();
        return redirect('contas_receber/listar?situacao=pagas')->with('mensagem', "Baixa da conta {$conta->documento} desfeita.");
    }

    // ---------- Relatório por período (data da venda) ----------

    function relatorio(Request $request) {
        $dados = $this->dadosRelatorio($request);
        return view('relatorio_contas_receber', $dados);
    }

    function relatorioPdf(Request $request) {
        $dados = $this->dadosRelatorio($request);
        $pdf = Pdf::loadView('pdf_contas_receber', $dados);
        return $pdf->download('contas_receber.pdf');
    }

    private function dadosRelatorio(Request $request): array {
        $request->validate(
            [
                'inicio' => ['nullable', 'date'],
                'fim'    => ['nullable', 'date', 'after_or_equal:inicio'],
            ],
            [
                'inicio.date'        => 'Data inicial inválida',
                'fim.date'           => 'Data final inválida',
                'fim.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial',
            ]
        );

        // padrão: do início do mês retrasado até hoje
        $inicio = $request->get('inicio', today()->subMonths(2)->startOfMonth()->format('Y-m-d'));
        $fim = $request->get('fim', today()->format('Y-m-d'));

        // contas das vendas feitas dentro do período
        $contas = ContaReceber::with(['cliente', 'venda'])
            ->whereHas('venda', function ($q) use ($inicio, $fim) {
                $q->whereDate('data', '>=', $inicio)->whereDate('data', '<=', $fim);
            })
            ->orderBy('data_vencimento')
            ->get();

        $totais = [
            'vencidos' => 0,   // não pagos com vencimento já passado
            'a_vencer' => 0,   // não pagos que ainda vão vencer
            'pagos'    => 0,
        ];
        foreach ($contas as $conta) {
            if ($conta->paga()) {
                $totais['pagos'] += $conta->valor;
            } elseif ($conta->data_vencimento->lt(today())) {
                $totais['vencidos'] += $conta->valor;
            } else {
                $totais['a_vencer'] += $conta->valor;
            }
        }

        return compact('contas', 'totais', 'inicio', 'fim');
    }
}

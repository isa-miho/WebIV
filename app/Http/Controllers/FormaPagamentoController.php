<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormaPagamentoItemRequest;
use App\Http\Requests\FormaPagamentoRequest;
use App\Models\Compra;
use App\Models\FormaPagamento;
use App\Models\FormaPagamentoItem;
use App\Models\Venda;
use Illuminate\Http\Request;

class FormaPagamentoController extends Controller
{
    function listar() {
        $formas = FormaPagamento::orderBy('numero_parcelas')->orderBy('descricao')->with('itens')->paginate(10);
        return view('listagem_forma_pagamento', compact('formas'));
    }

    function novo() {
        $forma = new FormaPagamento();
        $forma->id = 0;
        $forma->descricao = '';
        $forma->numero_parcelas = 1;
        return view('formulario_forma_pagamento', compact('forma'));
    }

    function salvar(FormaPagamentoRequest $request) {
        if ($request->id == 0) {
            $forma = new FormaPagamento();
        } else {
            $forma = FormaPagamento::find($request->id);
        }
        $forma->descricao = $request->descricao;
        $forma->numero_parcelas = $request->numero_parcelas;
        $forma->save();

        return redirect('forma_pagamento/itens/'.$forma->id);
    }

    function editar($id) {
        $forma = FormaPagamento::find($id);
        return view('formulario_forma_pagamento', compact('forma'));
    }

    function excluir($id) {
        $forma = FormaPagamento::find($id);

        if (Compra::where('forma_pagamento_id', $id)->exists() || Venda::where('forma_pagamento_id', $id)->exists()) {
            return redirect('forma_pagamento/listar')
                ->with('erro', "Não é possível excluir \"{$forma->descricao}\": ela já foi usada em compras ou vendas.");
        }

        $forma->delete(); // as parcelas são excluídas junto (cascade)
        return redirect('forma_pagamento/listar')->with('mensagem', 'Forma de pagamento excluída.');
    }

    // ---------- Parcelas (itens) ----------

    function itens($id) {
        $forma = FormaPagamento::with('itens')->find($id);

        $item = new FormaPagamentoItem();
        $item->id = 0;
        $item->forma_pagamento_id = $forma->id;
        // sugere a porcentagem que falta e o próximo vencimento
        $item->porcentagem = max(0, round(100 - $forma->totalPorcentagem(), 2));
        $item->dia = $forma->itens->count() == 0 ? 0 : $forma->itens->max('dia') + 30;

        return view('itens_forma_pagamento', compact('forma', 'item'));
    }

    function salvarItem(FormaPagamentoItemRequest $request) {
        $forma = FormaPagamento::with('itens')->find($request->forma_pagamento_id);

        if ($request->id == 0) {
            $item = new FormaPagamentoItem();
            if ($forma->itens->count() >= $forma->numero_parcelas) {
                return back()->withInput()->withErrors(
                    "Esta forma de pagamento tem {$forma->numero_parcelas} parcela(s), e todas já foram cadastradas.");
            }
            $outras = $forma->totalPorcentagem();
        } else {
            $item = FormaPagamentoItem::find($request->id);
            $outras = $forma->totalPorcentagem() - $item->porcentagem;
        }

        if (round($outras + $request->porcentagem, 2) > 100) {
            return back()->withInput()->withErrors(
                'A soma das porcentagens passaria de 100%. Disponível: ' . number_format(100 - $outras, 2, ',', '.') . '%.');
        }

        $item->forma_pagamento_id = $forma->id;
        $item->porcentagem = $request->porcentagem;
        $item->dia = $request->dia;
        $item->save();

        return redirect('forma_pagamento/itens/'.$forma->id);
    }

    function editarItem($id) {
        $item = FormaPagamentoItem::find($id);
        $forma = FormaPagamento::with('itens')->find($item->forma_pagamento_id);
        return view('itens_forma_pagamento', compact('forma', 'item'));
    }

    function excluirItem($id) {
        $item = FormaPagamentoItem::find($id);
        $forma_id = $item->forma_pagamento_id;
        $item->delete();
        return redirect('forma_pagamento/itens/'.$forma_id);
    }

    // Preenche todas as parcelas automaticamente: porcentagens iguais,
    // vencimentos de 30 em 30 dias (à vista = dia 0)
    function gerarParcelas($id) {
        $forma = FormaPagamento::find($id);
        FormaPagamentoItem::where('forma_pagamento_id', $id)->delete();

        $n = $forma->numero_parcelas;
        $porcentagem = floor(10000 / $n) / 100;   // ex.: 3 parcelas -> 33,33%
        for ($i = 1; $i <= $n; $i++) {
            $item = new FormaPagamentoItem();
            $item->forma_pagamento_id = $forma->id;
            // a última parcela completa os 100%
            $item->porcentagem = $i == $n ? round(100 - $porcentagem * ($n - 1), 2) : $porcentagem;
            $item->dia = $n == 1 ? 0 : 30 * $i;
            $item->save();
        }

        return redirect('forma_pagamento/itens/'.$forma->id)->with('mensagem', 'Parcelas geradas automaticamente. Ajuste se precisar.');
    }
}

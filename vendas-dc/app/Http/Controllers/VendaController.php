<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Parcela;
use App\Models\FormaPagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class VendaController extends Controller
{
    public function index(Request $request)
    {
        $vendas = Venda::with('cliente', 'formaPagamento')
            ->where('user_id', Auth::id())
            ->when($request->data_inicio, fn($q) => $q->whereDate('created_at', '>=', $request->data_inicio))
            ->when($request->data_fim, fn($q) => $q->whereDate('created_at', '<=', $request->data_fim))
            ->when($request->cliente, fn($q) => 
                $q->whereHas('cliente', fn($q2) => $q2->where('nome', 'like', '%'.$request->cliente.'%'))
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vendas.index', compact('vendas'));
    }

    public function edit($id)
    {
        $venda = Venda::with('itens.produto', 'parcelas', 'cliente')->findOrFail($id);
        $clientes = Cliente::all();
        $produtos = Produto::all();
        $formas = FormaPagamento::all();

        // Passa os dados para a view
        return view('vendas.edit', compact('venda', 'clientes', 'produtos', 'formas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();
        $formas = FormaPagamento::all();
        return view('vendas.create', compact('clientes', 'produtos', 'formas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'forma_pagamento_id' => 'required|exists:forma_pagamentos,id',
            'produtos' => 'required|array|min:1',
            'quantidades' => 'required|array|min:1',
            'valores' => 'required|array|min:1',
            'parcelas_valores' => 'required|array|min:1',
            'parcelas_datas' => 'required|array|min:1',
        ]);

        $valor_total = array_sum($request->parcelas_valores);

        $venda = Venda::create([
            'user_id' => Auth::id(),
            'cliente_id' => $request->cliente_id,
            'forma_pagamento_id' => $request->forma_pagamento_id,
            'valor_total' => $valor_total,
        ]);

        foreach ($request->produtos as $i => $produto_id) {
            $venda->itens()->create([
                'produto_id' => $produto_id,
                'quantidade' => $request->quantidades[$i],
                'valor_unitario' => $request->valores[$i],
            ]);
        }

        foreach ($request->parcelas_valores as $i => $valor) {
            Parcela::create([
                'venda_id' => $venda->id,
                'valor' => $valor,
                'data_vencimento' => $request->parcelas_datas[$i],
            ]);
        }

        return redirect()->route('vendas.index')->with('success', 'Venda cadastrada com sucesso!');
    }

    public function update(Request $request, $id)
{
    $venda = Venda::findOrFail($id);

    // Validação básica (pode ser expandida)
    $request->validate([
        'cliente_id' => 'nullable|exists:clientes,id',
        'forma_pagamento_id' => 'required|exists:formas_pagamento,id',
        'produtos' => 'required|array',
        'quantidades' => 'required|array',
        'valores' => 'required|array',
        'parcelas_valores' => 'required|array',
        'parcelas_datas' => 'required|array',
    ]);

    // Atualiza dados da venda
    $venda->cliente_id = $request->cliente_id;
    $venda->forma_pagamento_id = $request->forma_pagamento_id;
    $venda->save();

    // Atualiza itens: delete os antigos e insere os novos
    $venda->itens()->delete();

    $total = 0;
    foreach ($request->produtos as $index => $produtoId) {
        $quantidade = $request->quantidades[$index];
        $valor = $request->valores[$index];
        $total += $quantidade * $valor;

        $venda->itens()->create([
            'produto_id' => $produtoId,
            'quantidade' => $quantidade,
            'valor_unitario' => $valor,
        ]);
    }

    // Atualiza total da venda
    $venda->valor_total = $total;
    $venda->save();

    // Atualiza parcelas: delete as antigas e cria novas
    $venda->parcelas()->delete();

    foreach ($request->parcelas_valores as $idx => $valorParcela) {
        $venda->parcelas()->create([
            'valor' => $valorParcela,
            'vencimento' => $request->parcelas_datas[$idx],
            'pago' => false,
        ]);
    }

    return redirect()->route('vendas.index')->with('success', 'Venda atualizada com sucesso!');
}

    public function exportarPdf($id)
    {
        $venda = Venda::with(['cliente', 'itens.produto', 'parcelas', 'formaPagamento'])->findOrFail($id);
        $pdf = Pdf::loadView('vendas.pdf', compact('venda'));
        return $pdf->download("venda_{$id}.pdf");
    }

    public function destroy($id)
{
    $venda = Venda::findOrFail($id);
    $venda->itens()->delete();
    $venda->parcelas()->delete();
    $venda->delete();

    return redirect()->route('vendas.index')->with('success', 'Venda excluída com sucesso!');
}

}

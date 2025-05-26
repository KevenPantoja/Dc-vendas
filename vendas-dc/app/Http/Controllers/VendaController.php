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
use App\Exports\RelatorioVendasExport;
use Maatwebsite\Excel\Facades\Excel;

class VendaController extends Controller
{
    // Listagem de vendas com filtros
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

    // Formulário de criação de venda
    public function create()
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();
        $formas = FormaPagamento::all();

        return view('vendas.create', compact('clientes', 'produtos', 'formas'));
    }

    // Armazenamento da venda
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
            'vencimento' => $request->parcelas_datas[$i],
        ]);
        }

        return redirect()->route('vendas.create')->with('success', 'Venda cadastrada com sucesso! Faça um novo cadastro.');
    }

    // Formulário de edição
    public function edit($id)
    {
        $venda = Venda::with('itens.produto', 'parcelas', 'cliente')->findOrFail($id);
        $clientes = Cliente::all();
        $produtos = Produto::all();
        $formas = FormaPagamento::all();

        return view('vendas.edit', compact('venda', 'clientes', 'produtos', 'formas'));
    }

    // Atualização da venda
    public function update(Request $request, $id)
    {
        $venda = Venda::findOrFail($id);

        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'forma_pagamento_id' => 'required|exists:forma_pagamentos,id',
            'produtos' => 'required|array',
            'quantidades' => 'required|array',
            'valores' => 'required|array',
            'parcelas_valores' => 'required|array',
            'parcelas_datas' => 'required|array',
        ]);

        $venda->cliente_id = $request->cliente_id;
        $venda->forma_pagamento_id = $request->forma_pagamento_id;
        $venda->save();

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

        $venda->valor_total = $total;
        $venda->save();

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

    // Geração de PDF da venda
    public function exportarPdf($id)
    {
        $venda = Venda::with(['cliente', 'itens.produto', 'parcelas', 'formaPagamento'])->findOrFail($id);
        $pdf = Pdf::loadView('vendas.pdf', compact('venda'));
        return $pdf->download("venda_{$id}.pdf");
    }

    // Exclusão de venda
    public function destroy($id)
    {
        $venda = Venda::findOrFail($id);
        $venda->itens()->delete();
        $venda->parcelas()->delete();
        $venda->delete();

        return redirect()->route('vendas.index')->with('success', 'Venda excluída com sucesso!');
    }

    // Formulário de criação de cliente
    public function createCliente()
    {
        return view('vendas.cliente_add');
    }

    // Armazenamento de cliente via formulário padrão
    public function storeCliente(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:20',
            'tipo' => 'required|in:Física,Jurídica',
        ]);

        Cliente::create($validated);

        return redirect()->back()->with('success', 'Cliente cadastrado com sucesso!');
    }

    // Armazenamento de cliente via AJAX
    public function storeClienteAjax(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'nullable|string|max:20|unique:clientes,cpf',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:clientes,email',
            'tipo_pessoa' => 'required|string|in:pf,pj',
        ]);

        $cliente = Cliente::create($request->only('nome', 'cpf', 'telefone', 'email', 'tipo_pessoa'));

        return response()->json([
            'success' => true,
            'cliente' => $cliente,
            'message' => 'Cliente cadastrado com sucesso via AJAX.'
        ]);
    }

    // Listagem de produtos
    public function indexProdutos()
    {
        $produtos = Produto::orderBy('nome')->paginate(10);
        return view('vendas.produtos', compact('produtos'));
    }

    // Formulário de criação de produto
    public function createProduto()
    {
        return view('vendas.produto_add');
    }

    // Armazenamento de produto
    public function storeProduto(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
        ]);

        Produto::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'estoque' => $request->estoque,
        ]);

        return redirect()->route('vendas.produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    // Relatório de vendas (visual)
    public function relatorio(Request $request)
    {
        $query = Venda::with(['cliente', 'formaPagamento', 'user']);

        if ($request->filled('data')) {
            $query->whereDate('created_at', $request->data);
        }

        if ($request->filled('mes')) {
            $query->whereMonth('created_at', date('m', strtotime($request->mes)))
                  ->whereYear('created_at', date('Y', strtotime($request->mes)));
        }

        $vendas = $query->paginate(10);
        $totalGeral = $query->sum('valor_total');

        return view('vendas.relatorio', compact('vendas', 'totalGeral'));
    }

    // Relatório PDF
    public function relatorioPdf(Request $request)
    {
        $query = Venda::with(['cliente', 'formaPagamento', 'user']);

        if ($request->filled('data')) {
            $query->whereDate('created_at', $request->data);
        }

        if ($request->filled('mes')) {
            $query->whereMonth('created_at', date('m', strtotime($request->mes)))
                  ->whereYear('created_at', date('Y', strtotime($request->mes)));
        }

        $vendas = $query->get();
        $totalGeral = $vendas->sum('valor_total');

        $pdf = Pdf::loadView('vendas.relatorio_pdf', compact('vendas', 'totalGeral'));
        return $pdf->download('relatorio_vendas.pdf');
    }

    // Relatório Excel
    public function relatorioExcel(Request $request)
    {
        return Excel::download(new RelatorioVendasExport($request), 'relatorio_vendas.xlsx');
    }
}

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Lista de Produtos</h1>
        <a href="{{ route('vendas.produtos.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
           Novo Produto
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border border-gray-300 rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Nome</th>
                <th class="border px-4 py-2 text-right">Valor de Venda (R$)</th>
                <th class="border px-4 py-2 text-center">Data do Cadastro</th>
                <th class="border px-4 py-2 text-right">Estoque</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produtos as $produto)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $produto->nome }}</td>
                    <td class="border px-4 py-2 text-right">{{ number_format($produto->preco, 2, ',', '.') }}</td>
                    <td class="border px-4 py-2 text-center">{{ $produto->created_at->format('d/m/Y') }}</td>
                    <td class="border px-4 py-2 text-right">{{ $produto->estoque }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border px-4 py-2 text-center">Nenhum produto cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $produtos->links() }}
    </div>

</div>
@endsection

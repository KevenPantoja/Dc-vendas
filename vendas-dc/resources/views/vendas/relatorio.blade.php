@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Relatório de Vendas</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($vendas->isEmpty())
        <p>Nenhuma venda encontrada.</p>
    @else
        <p><strong>Total Geral: </strong>R$ {{ number_format($totalGeral ?? 0, 2, ',', '.') }}</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendas as $venda)
                <tr>
                    <td>{{ $venda->id }}</td>
                    <td>{{ $venda->cliente->nome ?? 'N/A' }}</td>
                    <td>{{ $venda->created_at->format('d/m/Y') }}</td>
                    <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('vendas.pdf', $venda->id) }}" class="btn btn-sm btn-primary" target="_blank">Baixar PDF</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginação --}}
        <div>
            {{ $vendas->links() }}
        </div>
    @endif
</div>
@endsection

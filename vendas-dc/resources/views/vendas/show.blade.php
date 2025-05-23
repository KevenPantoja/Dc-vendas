@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h3>Detalhes da Venda #{{ $venda->id }}</h3>

  <div class="mb-3">
    <strong>Cliente:</strong> {{ $venda->cliente ? $venda->cliente->nome : 'Não informado' }}
  </div>

  <div class="mb-3">
    <strong>Data da Venda:</strong> {{ $venda->created_at->format('d/m/Y') }}
  </div>

  <div class="mb-3">
    <strong>Forma de Pagamento:</strong> {{ $venda->formaPagamento->descricao ?? '-' }}
  </div>

  <div class="mb-3">
    <strong>Valor Total:</strong> R$ {{ number_format($venda->valor_total, 2, ',', '.') }}
  </div>

  <h5>Itens da Venda</h5>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Produto</th>
        <th>Quantidade</th>
        <th>Valor Unitário (R$)</th>
        <th>Subtotal (R$)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($venda->itens as $item)
      <tr>
        <td>{{ $item->produto->nome }}</td>
        <td>{{ $item->quantidade }}</td>
        <td>{{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
        <td>{{ number_format($item->quantidade * $item->valor_unitario, 2, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <h5>Parcelas</h5>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Valor (R$)</th>
        <th>Data de Vencimento</th>
        <th>Pago</th>
      </tr>
    </thead>
    <tbody>
      @foreach($venda->parcelas as $parcela)
      <tr>
        <td>{{ number_format($parcela->valor, 2, ',', '.') }}</td>
        <td>{{ \Carbon\Carbon::parse($parcela->vencimento)->format('d/m/Y') }}</td>
        <td>{{ $parcela->pago ? 'Sim' : 'Não' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <a href="{{ route('vendas.index') }}" class="btn btn-secondary">Voltar</a>
  <a href="{{ route('vendas.exportarPdf', $venda->id) }}" class="btn btn-success" target="_blank">Exportar PDF</a>
</div>
@endsection

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Venda #{{ $venda->id }}</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    h1, h3 { text-align: center; }
  </style>
</head>
<body>
  <h1>Relatório da Venda #{{ $venda->id }}</h1>
  <h3>Cliente: {{ $venda->cliente?->nome ?? 'Sem cliente' }}</h3>
  <h3>Valor Total: R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</h3>

  <h4>Itens</h4>
  <table>
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

  <h4>Parcelas</h4>
  <table>
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
</body>
</html>

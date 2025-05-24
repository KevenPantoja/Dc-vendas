<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Vendas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Relatório de Vendas</h2>
    <p>Data de geração: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Forma de Pagamento</th>
                <th>Valor Total (R$)</th>
                <th>Usuário</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendas as $venda)
            <tr>
                <td>{{ $venda->id }}</td>
                <td>{{ $venda->cliente->nome ?? '---' }}</td>
                <td>{{ $venda->formaPagamento->nome }}</td>
                <td>{{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                <td>{{ $venda->user->name }}</td>
                <td>{{ $venda->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

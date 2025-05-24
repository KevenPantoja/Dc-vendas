@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Minhas Vendas</h3>
    <a href="{{ route('vendas.create') }}" class="btn btn-primary">Cadastrar Nova Venda</a>
  </div>

  <div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('vendas.createCliente') }}" class="btn btn-secondary me-2">
    <i class="bi bi-plus-circle"></i> Cadastrar Cliente
    </a>
  </div>

  <div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('vendas.produtos.index') }}" class="btn btn-primary">Lista Produtos</a>
  </div>

  <div class="mb-3 text-end">
    <a href="{{ route('vendas.relatorio') }}" class="btn btn-primary">
        📊 Ver Relatório de Vendas
    </a>
</div>



  <!-- Filtros -->
  <form method="GET" action="{{ route('vendas.index') }}" class="row g-3 mb-4">
    <div class="col-md-3">
      <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="form-control" placeholder="Data Início">
    </div>
    <div class="col-md-3">
      <input type="date" name="data_fim" value="{{ request('data_fim') }}" class="form-control" placeholder="Data Fim">
    </div>
    <div class="col-md-4">
      <input type="text" name="cliente" value="{{ request('cliente') }}" class="form-control" placeholder="Nome do Cliente">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-secondary w-100">Filtrar</button>
    </div>
  </form>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <!-- Tabela de vendas -->
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Data</th>
        <th>Valor Total</th>
        <th>Forma Pagamento</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @forelse($vendas as $venda)
      <tr>
        <td>{{ $venda->id }}</td>
        <td>{{ $venda->cliente ? $venda->cliente->nome : '-' }}</td>
        <td>{{ $venda->created_at->format('d/m/Y') }}</td>
        <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
        <td>{{ $venda->formaPagamento->descricao ?? '-' }}</td>
        <td>
          <a href="{{ route('vendas.show', $venda->id) }}" class="btn btn-sm btn-info">Detalhes</a>
          <a href="{{ route('vendas.exportarPdf', $venda->id) }}" class="btn btn-sm btn-success" target="_blank">PDF</a>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center">Nenhuma venda encontrada.</td></tr>
      @endforelse
    </tbody>
  </table>

  <!-- Paginação -->
  <div>
    {{ $vendas->appends(request()->query())->links() }}
  </div>
</div>
@endsection

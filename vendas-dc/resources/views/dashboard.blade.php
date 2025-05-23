@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Minhas Vendas</h3>
    <div>
      <a href="{{ route('vendas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Cadastrar Nova Venda
      </a>
    </div>
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
        <td>{{ $venda->forma_pagamento->descricao ?? '-' }}</td>
        <td>
          <a href="{{ route('vendas.show', $venda->id) }}" class="btn btn-sm btn-info">Detalhes</a>
          <a href="{{ route('vendas.pdf', $venda->id) }}" class="btn btn-sm btn-success" target="_blank">PDF</a>
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

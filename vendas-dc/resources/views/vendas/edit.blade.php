@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h3>Editar Venda #{{ $venda->id }}</h3>

  <form action="{{ route('vendas.update', $venda->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Cliente -->
    <div class="mb-3">
      <label for="cliente_id" class="form-label">Cliente (opcional)</label>
      <select name="cliente_id" id="cliente_id" class="form-select">
        <option value="">-- Selecionar Cliente --</option>
        @foreach($clientes as $cliente)
        <option value="{{ $cliente->id }}" {{ $venda->cliente_id == $cliente->id ? 'selected' : '' }}>
          {{ $cliente->nome }}
        </option>
        @endforeach
      </select>
    </div>

    <!-- Itens -->
    <h5>Itens da Venda</h5>
    <table class="table" id="itens-table">
      <thead>
        <tr>
          <th>Produto</th>
          <th>Quantidade</th>
          <th>Valor Unitário (R$)</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($venda->itens as $item)
        <tr>
          <td>
            <select name="produtos[]" class="form-select" required>
              <option value="">-- Selecione --</option>
              @foreach($produtos as $produto)
              <option value="{{ $produto->id }}" {{ $item->produto_id == $produto->id ? 'selected' : '' }}>
                {{ $produto->nome }}
              </option>
              @endforeach
            </select>
          </td>
          <td><input type="number" name="quantidades[]" class="form-control" min="1" value="{{ $item->quantidade }}" required></td>
          <td><input type="number" step="0.01" name="valores[]" class="form-control" min="0" value="{{ $item->valor_unitario }}" required></td>
          <td><button type="button" class="btn btn-danger btn-remove-item">Remover</button></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <button type="button" class="btn btn-secondary mb-3" id="add-item">Adicionar Item</button>

    <!-- Forma de Pagamento -->
    <div class="mb-3">
      <label for="forma_pagamento_id" class="form-label">Forma de Pagamento</label>
      <select name="forma_pagamento_id" id="forma_pagamento_id" class="form-select" required>
        <option value="">-- Selecione --</option>
        @foreach($formas as $forma)
        <option value="{{ $forma->id }}" {{ $venda->forma_pagamento_id == $forma->id ? 'selected' : '' }}>
          {{ $forma->descricao }}
        </option>
        @endforeach
      </select>
    </div>

    <!-- Parcelas -->
    <h5>Parcelas</h5>
    <table class="table" id="parcelas-table">
      <thead>
        <tr>
          <th>Valor (R$)</th>
          <th>Data de Vencimento</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($venda->parcelas as $parcela)
        <tr>
          <td><input type="number" step="0.01" name="parcelas_valores[]" class="form-control" min="0" value="{{ $parcela->valor }}" required></td>
          <td><input type="date" name="parcelas_datas[]" class="form-control" value="{{ $parcela->vencimento->format('Y-m-d') }}" required></td>
          <td><button type="button" class="btn btn-danger btn-remove-parcela">Remover</button></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <button type="button" class="btn btn-secondary mb-3" id="add-parcela">Adicionar Parcela</button>

    <button type="submit" class="btn btn-primary">Atualizar Venda</button>
  </form>
</div>

<script>
  // Mesma lógica do create para adicionar/remover itens e parcelas
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('add-item').addEventListener('click', function() {
      const tbody = document.querySelector('#itens-table tbody');
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          <select name="produtos[]" class="form-select" required>
            <option value="">-- Selecione --</option>
            @foreach($produtos as $produto)
            <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
            @endforeach
          </select>
        </td>
        <td><input type="number" name="quantidades[]" class="form-control" min="1" value="1" required></td>
        <td><input type="number" step="0.01" name="valores[]" class="form-control" min="0" required></td>
        <td><button type="button" class="btn btn-danger btn-remove-item">Remover</button></td>
      `;
      tbody.appendChild(tr);
    });

    document.querySelector('#itens-table').addEventListener('click', function(e) {
      if(e.target.classList.contains('btn-remove-item')) {
        e.target.closest('tr').remove();
      }
    });

    document.getElementById('add-parcela').addEventListener('click', function() {
      const tbody = document.querySelector('#parcelas-table tbody');
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="number" step="0.01" name="parcelas_valores[]" class="form-control" min="0" required></td>
        <td><input type="date" name="parcelas_datas[]" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger btn-remove-parcela">Remover</button></td>
      `;
      tbody.appendChild(tr);
    });

    document.querySelector('#parcelas-table').addEventListener('click', function(e) {
      if(e.target.classList.contains('btn-remove-parcela')) {
        e.target.closest('tr').remove();
      }
    });
  });
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h3>Cadastrar Nova Venda</h3>

  <form action="{{ route('vendas.store') }}" method="POST">
    @csrf

    <!-- Cliente -->
    <div class="mb-3">
      <label for="cliente_id" class="form-label">Cliente (opcional)</label>
      <select name="cliente_id" id="cliente_id" class="form-select">
        <option value="">-- Selecionar Cliente --</option>
        @foreach($clientes as $cliente)
        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
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
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <select name="produtos[]" class="form-select" required>
              <option value="">-- Selecione --</option>
              @foreach($produtos as $produto)
              <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}">{{ $produto->nome }}</option>
              @endforeach
            </select>
          </td>
          <td><input type="number" name="quantidades[]" class="form-control" min="1" value="1" required></td>
          <td><input type="number" step="0.01" name="valores[]" class="form-control" min="0" required></td>
          <td><button type="button" class="btn btn-danger btn-remove-item">Remover</button></td>
          <td class="valor-total">R$ 0,00</td>
        </tr>
      </tbody>
    </table>
    <button type="button" class="btn btn-secondary mb-3" id="add-item">Adicionar Item</button>

    <!-- Forma de Pagamento -->
    <div class="mb-3">
      <label for="forma_pagamento_id" class="form-label">Forma de Pagamento</label>
      <select name="forma_pagamento_id" id="forma_pagamento_id" class="form-select" required>
        <option value="">-- Selecione --</option>
        @foreach($formas as $forma)
        <option value="{{ $forma->id }}">{{ $forma->descricao }}</option>
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
        <tr>
          <td><input type="number" step="0.01" name="parcelas_valores[]" class="form-control" min="0" required></td>
          <td><input type="date" name="parcelas_datas[]" class="form-control" required></td>
          <td><button type="button" class="btn btn-danger btn-remove-parcela">Remover</button></td>
        </tr>
      </tbody>
    </table>
    <button type="button" class="btn btn-secondary mb-3" id="add-parcela">Adicionar Parcela</button>

    <button type="submit" class="btn btn-primary">Salvar Venda</button>
  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tabelaItens = document.querySelector('#itens-table tbody');

    function calcularTotalItem(row) {
      const quantidade = parseFloat(row.querySelector('input[name="quantidades[]"]').value) || 0;
      const valorUnitario = parseFloat(row.querySelector('input[name="valores[]"]').value) || 0;
      const total = quantidade * valorUnitario;
      row.querySelector('.valor-total').textContent = `R$ ${total.toFixed(2)}`;
    }

    tabelaItens.addEventListener('change', function (e) {
      const row = e.target.closest('tr');

      if (e.target.name === 'produtos[]') {
        const preco = e.target.selectedOptions[0].dataset.preco;
        row.querySelector('input[name="valores[]"]').value = preco;
        calcularTotalItem(row);
      }

      if (e.target.name === 'quantidades[]' || e.target.name === 'valores[]') {
        calcularTotalItem(row);
      }
    });

    tabelaItens.addEventListener('click', function (e) {
      if (e.target.classList.contains('btn-remove-item')) {
        const rows = tabelaItens.querySelectorAll('tr');
        if (rows.length > 1) {
          e.target.closest('tr').remove();
        } else {
          alert('Deve haver pelo menos um item.');
        }
      }
    });

    document.getElementById('add-item').addEventListener('click', function () {
      const novaLinha = tabelaItens.querySelector('tr').cloneNode(true);

      novaLinha.querySelector('select').selectedIndex = 0;
      novaLinha.querySelector('input[name="quantidades[]"]').value = 1;
      novaLinha.querySelector('input[name="valores[]"]').value = '';
      novaLinha.querySelector('.valor-total').textContent = 'R$ 0,00';

      tabelaItens.appendChild(novaLinha);
    });

    // Parcelas
    document.getElementById('add-parcela').addEventListener('click', function () {
      const tbody = document.querySelector('#parcelas-table tbody');
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="number" step="0.01" name="parcelas_valores[]" class="form-control" min="0" required></td>
        <td><input type="date" name="parcelas_datas[]" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger btn-remove-parcela">Remover</button></td>
      `;
      tbody.appendChild(tr);
    });

    document.querySelector('#parcelas-table').addEventListener('click', function (e) {
      if (e.target.classList.contains('btn-remove-parcela')) {
        const rows = document.querySelectorAll('#parcelas-table tbody tr');
        if (rows.length > 1) {
          e.target.closest('tr').remove();
        } else {
          alert('Deve haver pelo menos uma parcela.');
        }
      }
    });
  });
</script>
@endsection

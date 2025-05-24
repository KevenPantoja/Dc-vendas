@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h3>Cadastrar Nova Venda</h3>

  <form method="POST" action="{{ route('vendas.store') }}">
    @csrf

    <!-- Cliente -->
    <div class="mb-3">
      <label for="cliente_id" class="form-label">Cliente</label>
      <select name="cliente_id" id="cliente_id" class="form-select">
        <option value="">-- Selecionar Cliente --</option>
        @foreach($clientes as $cliente)
          <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
        @endforeach
      </select>
    </div>

    <!-- Itens da Venda -->
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

    <!-- Total Geral -->
    <div class="mb-3">
      <h5>Total da Venda: <span id="total-geral">R$ 0,00</span></h5>
    </div>

    <!-- Forma de Pagamento -->
   <select name="forma_pagamento_id" id="forma_pagamento_id" class="form-select" required>
      <option value="">-- Selecione --</option>
      <!-- Opção fixa para forma de pagamento personalizada -->
      <option value="1" data-tipo="personalizado">Pagamento Personalizado</option>
    </select>


    <!-- Opções de Parcelamento -->
    <div id="parcelamento-opcoes">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="num_parcelas" class="form-label">Número de Parcelas</label>
          <input type="number" id="num_parcelas" class="form-control" min="1" value="1">
        </div>
        <div class="col-md-4">
          <label for="data_inicio_parcelas" class="form-label">Data Inicial das Parcelas</label>
          <input type="date" id="data_inicio_parcelas" class="form-control" required>
          <div id="erro-data-inicial" class="text-danger mt-1 d-none">A data inicial não pode ser anterior a hoje.</div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="button" class="btn btn-info w-100" id="gerar-parcelas">Gerar Parcelas</button>
        </div>
      </div>
    </div>

    <!-- Tabela de Parcelas -->
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
          <td><input type="date" name="parcelas_datas[]" class="form-control parcelas-data" required></td>
          <td><button type="button" class="btn btn-danger btn-remove-parcela">Remover</button></td>
        </tr>
      </tbody>
    </table>
    <button type="button" class="btn btn-secondary mb-3" id="add-parcela">Adicionar Parcela</button>

    <button type="submit" class="btn btn-primary">Salvar</button>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const tabelaItens = document.querySelector('#itens-table tbody');
  const totalGeralSpan = document.getElementById('total-geral');
  const formVenda = document.getElementById('form-venda');

  function calcularTotalItem(row) {
    const quantidade = parseFloat(row.querySelector('input[name="quantidades[]"]').value) || 0;
    const valorUnitario = parseFloat(row.querySelector('input[name="valores[]"]').value) || 0;
    const total = quantidade * valorUnitario;
    row.querySelector('.valor-total').textContent = `R$ ${total.toFixed(2)}`;
  }

  function calcularTotalVenda() {
    let total = 0;
    document.querySelectorAll('#itens-table tbody tr').forEach(row => {
      const qtd = parseFloat(row.querySelector('input[name="quantidades[]"]').value) || 0;
      const val = parseFloat(row.querySelector('input[name="valores[]"]').value) || 0;
      total += qtd * val;
    });
    totalGeralSpan.textContent = `R$ ${total.toFixed(2)}`;
    return total;
  }

  tabelaItens.addEventListener('change', function (e) {
    const row = e.target.closest('tr');

    if (e.target.name === 'produtos[]') {
      const preco = e.target.selectedOptions[0]?.dataset.preco || 0;
      row.querySelector('input[name="valores[]"]').value = preco;
      calcularTotalItem(row);
      calcularTotalVenda();
    }

    if (e.target.name === 'quantidades[]' || e.target.name === 'valores[]') {
      calcularTotalItem(row);
      calcularTotalVenda();
    }
  });

  tabelaItens.addEventListener('input', function (e) {
    // Para captura imediata em inputs tipo number
    if (e.target.name === 'quantidades[]' || e.target.name === 'valores[]') {
      const row = e.target.closest('tr');
      calcularTotalItem(row);
      calcularTotalVenda();
    }
  });

  tabelaItens.addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-item')) {
      const rows = tabelaItens.querySelectorAll('tr');
      if (rows.length > 1) {
        e.target.closest('tr').remove();
        calcularTotalVenda();
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
    calcularTotalVenda();
  });

  // Parcelas
  document.getElementById('add-parcela').addEventListener('click', function () {
    const tbody = document.querySelector('#parcelas-table tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input type="number" step="0.01" name="parcelas_valores[]" class="form-control" min="0" required></td>
      <td><input type="date" name="parcelas_datas[]" class="form-control parcelas-data" required></td>
      <td><button type="button" class="btn btn-danger btn-remove-parcela">Remover</button></td>
    `;
    tbody.appendChild(tr);
  });

  document.querySelector('#parcelas-table tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-parcela')) {
      const linhas = this.querySelectorAll('tr');
      if (linhas.length > 1) {
        e.target.closest('tr').remove();
      } else {
        alert('Deve haver pelo menos uma parcela.');
      }
    }
  });

  // Validação de datas das parcelas: não aceitar datas anteriores a hoje
  function validarDataVencimento(input) {
    const hoje = new Date();
    hoje.setHours(0, 0, 0, 0);
    const valorData = new Date(input.value);
    if (valorData < hoje) {
      input.classList.add('is-invalid');
      return false;
    } else {
      input.classList.remove('is-invalid');
      return true;
    }
  }

  // Ao alterar data da parcela, validar
  document.querySelector('#parcelas-table tbody').addEventListener('change', function(e){
    if(e.target.classList.contains('parcelas-data')){
      validarDataVencimento(e.target);
    }
  });

  // Validação da data inicial das parcelas
  const inputDataInicio = document.getElementById('data_inicio_parcelas');
  const erroDataInicial = document.getElementById('erro-data-inicial');

  function validarDataInicio() {
    const hoje = new Date();
    hoje.setHours(0,0,0,0);
    const dataInicio = new Date(inputDataInicio.value);
    if (dataInicio < hoje) {
      erroDataInicial.classList.remove('d-none');
      inputDataInicio.classList.add('is-invalid');
      return false;
    } else {
      erroDataInicial.classList.add('d-none');
      inputDataInicio.classList.remove('is-invalid');
      return true;
    }
  }

  inputDataInicio.addEventListener('change', validarDataInicio);

  // Botão gerar parcelas
  document.getElementById('gerar-parcelas').addEventListener('click', function(){
    if (!validarDataInicio()) {
      alert('Data inicial das parcelas não pode ser anterior a hoje.');
      return;
    }

    const numParcelas = parseInt(document.getElementById('num_parcelas').value);
    if (isNaN(numParcelas) || numParcelas < 1) {
      alert('Número de parcelas inválido.');
      return;
    }

    const totalVenda = calcularTotalVenda();
    if (totalVenda <= 0) {
      alert('O total da venda deve ser maior que zero para gerar parcelas.');
      return;
    }

    // Calcula valor base de cada parcela
    const valorBase = Math.floor((totalVenda / numParcelas) * 100) / 100; // Trunca para 2 decimais
    let somaParcelas = 0;

    const tbodyParcelas = document.querySelector('#parcelas-table tbody');
    tbodyParcelas.innerHTML = '';

    const dataInicial = new Date(inputDataInicio.value);

    for (let i = 0; i < numParcelas; i++) {
      let valorParcela = valorBase;
      somaParcelas += valorBase;

      if (i === numParcelas -1) {
        // Ajusta última parcela para somar corretamente com centavos
        valorParcela = totalVenda - (valorBase * (numParcelas -1));
        valorParcela = Math.round(valorParcela * 100) / 100;
      }

      // Calcula data da parcela (incrementa meses)
      let dataParcela = new Date(dataInicial);
      dataParcela.setMonth(dataParcela.getMonth() + i);

      // Garante que data da parcela não seja anterior a hoje
      const hoje = new Date();
      hoje.setHours(0,0,0,0);
      if (dataParcela < hoje) {
        dataParcela = new Date(hoje);
      }

      const dataFormatada = dataParcela.toISOString().split('T')[0];

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="number" step="0.01" name="parcelas_valores[]" class="form-control" min="0" required value="${valorParcela.toFixed(2)}"></td>
        <td><input type="date" name="parcelas_datas[]" class="form-control parcelas-data" required value="${dataFormatada}"></td>
        <td><button type="button" class="btn btn-danger btn-remove-parcela">Remover</button></td>
      `;
      tbodyParcelas.appendChild(tr);
    }
  });

  // Exibe/oculta aviso de forma de pagamento e controla parcelas permitidas
  const selectFormaPagamento = document.getElementById('forma_pagamento_id');
  const avisoPagamento = document.getElementById('aviso-pagamento');
  const parcelasOpcoes = document.getElementById('parcelamento-opcoes');

  selectFormaPagamento.addEventListener('change', function(){
    const tipo = this.selectedOptions[0]?.dataset.tipo || '';
    if(tipo === 'pix' || tipo === 'débito'){
      avisoPagamento.classList.remove('d-none');
      parcelasOpcoes.style.display = 'none';
      // Limpa parcelas existentes
      document.querySelector('#parcelas-table tbody').innerHTML = `
        <tr>
          <td><input type="number" step="0.01" name="parcelas_valores[]" class="form-control" min="0" required></td>
          <td><input type="date" name="parcelas_datas[]" class="form-control parcelas-data" required></td>
          <td><button type="button" class="btn btn-danger btn-remove-parcela">Remover</button></td>
        </tr>`;
    } else {
      avisoPagamento.classList.add('d-none');
      parcelasOpcoes.style.display = 'block';
    }
  });

  // Validação final antes de enviar o formulário
  formVenda.addEventListener('submit', function(e){
    // Verifica se todas as datas das parcelas são >= hoje
    let datasValidas = true;
    document.querySelectorAll('.parcelas-data').forEach(input => {
      if(!validarDataVencimento(input)){
        datasValidas = false;
      }
    });

    // Verifica data inicial parcelas
    if(!validarDataInicio()){
      datasValidas = false;
    }

    if(!datasValidas){
      alert('Corrija as datas das parcelas para que sejam iguais ou posteriores a hoje.');
      e.preventDefault();
      return false;
    }
  });

  // Inicializa valores
  calcularTotalVenda();
});
</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Cadastrar Novo Cliente</h4>

    <form method="POST" action="{{ route('clientes.store') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nome" class="form-label">Nome Completo / Razão Social</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label for="cpf" class="form-label">CPF ou CNPJ</label>
                <input type="text" name="cpf" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" class="form-select" required>
                    <option value="Física">Pessoa Física</option>
                    <option value="Jurídica">Pessoa Jurídica</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nome_social" class="form-label">Nome Fantasia / Nome Social</label>
                <input type="text" name="nome_social" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="rg" class="form-label">RG</label>
                <input type="text" name="rg" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="limite_vendas" class="form-label">Limite de Vendas (R$)</label>
                <input type="number" step="0.01" name="limite_vendas" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" name="telefone" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="celular" class="form-label">Celular (opcional)</label>
                <input type="text" name="celular" class="form-control">
            </div>
        </div>

        <h5 class="mt-4">Endereço</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="rua" class="form-label">Rua</label>
                <input type="text" name="rua" class="form-control">
            </div>
            <div class="col-md-2">
                <label for="numero" class="form-label">Número</label>
                <input type="text" name="numero" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" name="bairro" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="cep" class="form-label">CEP</label>
                <input type="text" name="cep" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Consumidor Final</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="consumidor_final" value="1" checked>
                    <label class="form-check-label">Sim</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="consumidor_final" value="0">
                    <label class="form-check-label">Não</label>
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label">Contribuinte</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="contribuinte" value="1" checked>
                    <label class="form-check-label">Sim</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="contribuinte" value="0">
                    <label class="form-check-label">Não</label>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Salvar Cliente</button>
            <a href="{{ route('vendas.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
@endsection

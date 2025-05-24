@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 600px;">

    <h1 class="mb-4">Cadastrar Novo Produto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendas.produtos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
            <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                class="form-control" />
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="preco" class="form-label">Preço (Valor de Venda) (R$) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="preco" id="preco" value="{{ old('preco') }}" required
                    class="form-control" />
            </div>
        </div>

        <div class="mb-3">
            <label for="estoque" class="form-label">Estoque <span class="text-danger">*</span></label>
            <input type="number" min="0" name="estoque" id="estoque" value="{{ old('estoque') }}" required
                class="form-control" />
        </div>

        <div class="d-flex justify-content-between align-items-center pt-3">
            <a href="{{ route('vendas.produtos.index') }}" class="btn btn-link">Voltar</a>
            <button type="submit" class="btn btn-primary">Salvar Produto</button>
        </div>

    </form>
</div>
@endsection

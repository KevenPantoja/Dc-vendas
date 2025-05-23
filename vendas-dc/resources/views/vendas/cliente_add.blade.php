@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h3 class="mb-4">Cadastrar Novo Cliente</h3>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <form action="{{ route('clientes.store') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label for="nome" class="form-label">Nome do Cliente</label>
      <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome') }}" required>
      @error('nome') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
      <label for="telefone" class="form-label">Telefone</label>
      <input type="text" name="telefone" id="telefone" class="form-control" value="{{ old('telefone') }}">
      @error('telefone') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">E-mail</label>
      <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
      @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="d-flex justify-content-between">
      <a href="{{ route('vendas.index') }}" class="btn btn-secondary">Voltar</a>
      <button type="submit" class="btn btn-primary">Salvar Cliente</button>
    </div>
  </form>
</div>
@endsection

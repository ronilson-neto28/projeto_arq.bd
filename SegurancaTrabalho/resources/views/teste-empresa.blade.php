@extends('layout.master2')

@section('content')
<div class="container mt-5">
  <h4>Teste de Cadastro de Empresa</h4>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('empresa.testar.salvar') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label for="nome" class="form-label">Nome da Empresa</label>
      <input type="text" name="nome" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="cnpj" class="form-label">CNPJ</label>
      <input type="text" name="cnpj" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
  </form>
</div>
@endsection

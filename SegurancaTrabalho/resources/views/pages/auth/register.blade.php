@extends('layout.master2')

@section('content')
<div class="row w-100 mx-0 auth-page">
  <div class="col-md-8 col-xl-6 mx-auto">
    <div class="card">
      <div class="row">
        <div class="col-md-4 pe-md-0">
          <div class="auth-side-wrapper" style="background-image: url('{{ asset('build/images/login-banner.png') }}')">
          </div>
        </div>
        <div class="col-md-8 ps-md-0">
          <div class="auth-form-wrapper px-4 py-5">
            <a href="#" class="nobleui-logo d-block mb-2">PRO<span>ATIVA</span></a>
            <h5 class="text-secondary fw-normal mb-4">Crie uma conta gratuita.</h5>
            <!--{{-- MENSAGEM DE SUCESSO --}}-->
            @if (session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif

            <!--{{-- MENSAGENS DE ERRO --}}-->
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <!--<form method="POST" action="{{ route('register.post') }}">-->
            <form method="POST" action="{{ route('register.post') }}" autocomplete="off">
              @csrf
              <div class="mb-3">
                <label for="name" class="form-label">Nome de Usuário</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nome de Usuário" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Endereço de E-mail</label>
                <input type="email" class="form-control" autocomplete="off" id="email" name="email" placeholder="Endereço de E-mail" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" autocomplete="new-password" id="password" name="password" placeholder="Senha" required>
              </div>
              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" autocomplete="new-password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Senha" required>
              </div>
              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="authCheck">
                <label class="form-check-label" for="authCheck">Lembre-se de mim</label>
              </div>
              <div>
                <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0">Cadastrar</button>
              </div>
              <p class="mt-3 text-secondary">Já tem uma conta? <a href="{{ route('login') }}">Entrar</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
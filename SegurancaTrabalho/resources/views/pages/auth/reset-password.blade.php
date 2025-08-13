@extends('layout.master2')

@section('content')
<div class="row w-100 mx-0 auth-page">
  <div class="col-md-8 col-xl-6 mx-auto">
    <div class="card">
      <div class="row">
        <div class="col-md-4 pe-md-0">
          <div class="auth-side-wrapper" style="background-image: url('{{ asset('build/images/login-banner.png') }}')"></div>
        </div>
        <div class="col-md-8 ps-md-0">
          <div class="auth-form-wrapper px-4 py-5">
            <h5 class="text-secondary fw-normal mb-4">Redefinir senha</h5>

            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
              @csrf

              <input type="hidden" name="token" value="{{ $token }}">
              <input type="hidden" name="email" value="{{ $email }}">

              <div class="mb-3">
                <label class="form-label">Nova senha</label>
                <input type="password" class="form-control" name="password" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Confirmar nova senha</label>
                <input type="password" class="form-control" name="password_confirmation" required>
              </div>

              <button type="submit" class="btn btn-primary">Salvar nova senha</button>

              <p class="mt-3 text-secondary"><a href="{{ route('login') }}">Voltar para login</a></p>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@extends('layout.master2')

@section('content')
<div class="row w-100 mx-0 auth-page">
  <div class="col-md-8 col-xl-6 mx-auto">
    <div class="card">
      <div class="row">
        <!--<div class="col-md-4 pe-md-0">
          <div class="auth-side-wrapper" style="background-image: url('{{ asset('build/images/login-banner.png') }}')">
          </div>
        </div>-->
        <div class="col-md-8 ps-md-0">
          <div class="auth-form-wrapper px-4 py-5">
            <a href="#" class="nobleui-logo d-block mb-2">PRO<span>ATIVA</span></a>
            <h5 class="text-secondary fw-normal mb-4">Digite o código enviado para seu e-mail</h5>

            <form method="POST" action="{{ route('password.confirm.code') }}">
              @csrf

              <div class="mb-3">
                <label for="code" class="form-label">Código de verificação</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Ex: 123456" required>
              </div>

              <div class="mb-3">
                <label for="new_password" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Nova senha" required>
              </div>

              <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Confirme a Nova Senha</label>
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirme a senha" required>
              </div>

              <button type="submit" class="btn btn-primary">Redefinir Senha</button>
            </form>

            <p class="mt-3 text-secondary"><a href="{{ route('login') }}">Voltar para login</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

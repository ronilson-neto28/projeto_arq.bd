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
            <h5 class="text-secondary fw-normal mb-4">Recupere sua senha</h5>

            @if(session('error'))
              <div class="alert alert-danger">
                {{ session('error') }}
              </div>
            @endif

            @if(session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif


            <form method="POST" action="{{ route('password.code.send') }}">
              @csrf

              <div class="mb-3">
                <label for="email" class="form-label">Informe seu e-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="exemplo@email.com" required>
              </div>

              <div>
                <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0">Enviar Código</button>
              </div>

              <p class="mt-3 text-secondary"><a href="{{ route('login') }}">Voltar para login</a></p>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

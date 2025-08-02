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
            <a href="#" class="nobleui-logo d-block mb-2">Noble<span>UI</span></a>
            <h5 class="text-secondary fw-normal mb-4">Bem-vindo de volta! Entre na sua conta.</h5>
            <form method="POST" action="{{ route('login.post') }}">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <!--<input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>-->
                <div class="input-group">
                  <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
                  <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                    <i id="toggleIcon" data-lucide="eye"></i>
                  </span>
                </div>
              </div>
              








              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Lembre-se de Mim</label>
              </div>
              <div>
                <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0">Login</button>
              </div>
              <p class="mt-3 text-secondary">Não tem uma conta? <a href="{{ route('register') }}">Inscrever-se</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById("password");
  const icon = document.getElementById("toggleIcon");

  // Alterna visibilidade
  if (input.type === "password") {
    input.type = "text";
    icon.setAttribute("data-lucide", "eye-off");
  } else {
    input.type = "password";
    icon.setAttribute("data-lucide", "eye");
  }

  // Atualiza o ícone Lucide
  lucide.createIcons();
}
</script>

@endsection
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
              <div class="form-check mb-3 d-flex justify-content-between align-items-center">
                <div>
                  <input type="checkbox" class="form-check-input" id="remember" name="remember">
                  <label class="form-check-label" for="remember">Lembre-se de Mim</label>
                </div>
                <div>
                  <a href="{{ route('password.request') }}" class="text-decoration-none">Esqueci minha senha</a>
                </div>
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

// Funcionalidade "Lembre-se de mim"
document.addEventListener('DOMContentLoaded', function() {
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const rememberCheckbox = document.getElementById('remember');
  const loginForm = document.querySelector('form');

  // Carregar dados salvos ao carregar a página
  loadSavedCredentials();

  // Salvar dados quando o checkbox for alterado
  rememberCheckbox.addEventListener('change', function() {
    if (this.checked) {
      saveCredentials();
    } else {
      clearSavedCredentials();
    }
  });

  // Salvar dados quando os campos forem alterados (se checkbox estiver marcado)
  emailInput.addEventListener('input', function() {
    if (rememberCheckbox.checked) {
      saveCredentials();
    }
  });

  passwordInput.addEventListener('input', function() {
    if (rememberCheckbox.checked) {
      saveCredentials();
    }
  });

  // Permitir login com Enter
  emailInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      if (rememberCheckbox.checked && emailInput.value && passwordInput.value) {
        loginForm.submit();
      } else if (!rememberCheckbox.checked) {
        passwordInput.focus();
      }
    }
  });

  passwordInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      if (rememberCheckbox.checked && emailInput.value && passwordInput.value) {
        loginForm.submit();
      } else if (!rememberCheckbox.checked && emailInput.value && passwordInput.value) {
        loginForm.submit();
      }
    }
  });

  function saveCredentials() {
    if (emailInput.value) {
      localStorage.setItem('remember_email', emailInput.value);
    }
    if (passwordInput.value) {
      localStorage.setItem('remember_password', passwordInput.value);
    }
    localStorage.setItem('remember_me_checked', 'true');
  }

  function loadSavedCredentials() {
    const savedEmail = localStorage.getItem('remember_email');
    const savedPassword = localStorage.getItem('remember_password');
    const rememberChecked = localStorage.getItem('remember_me_checked');

    if (rememberChecked === 'true') {
      rememberCheckbox.checked = true;
      if (savedEmail) {
        emailInput.value = savedEmail;
      }
      if (savedPassword) {
        passwordInput.value = savedPassword;
      }
    }
  }

  function clearSavedCredentials() {
    localStorage.removeItem('remember_email');
    localStorage.removeItem('remember_password');
    localStorage.removeItem('remember_me_checked');
  }
});
</script>

@endsection
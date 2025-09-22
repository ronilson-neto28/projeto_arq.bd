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
            <h5 class="text-secondary fw-normal mb-4">Verificação de Email</h5>
            
            <div class="alert alert-info mb-4">
              <i data-lucide="mail" class="me-2"></i>
              Enviamos um código de verificação de 6 dígitos para seu email. 
              O código é válido por <strong>15 minutos</strong>.
            </div>
            
            {{-- MENSAGEM DE SUCESSO --}}
            @if (session('success'))
              <div class="alert alert-success">
                <i data-lucide="check-circle" class="me-2"></i>
                {{ session('success') }}
              </div>
            @endif

            {{-- MENSAGENS DE ERRO --}}
            @if ($errors->any())
              <div class="alert alert-danger">
                <i data-lucide="alert-triangle" class="me-2"></i>
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            
            <form method="POST" action="{{ route('verification.verify') }}" autocomplete="off">
              @csrf
              <div class="mb-4">
                <label for="verification_code" class="form-label">Código de Verificação</label>
                <input 
                  type="text" 
                  class="form-control text-center" 
                  id="verification_code" 
                  name="verification_code" 
                  placeholder="000000" 
                  maxlength="6"
                  pattern="[0-9]{6}"
                  autocomplete="off"
                  style="font-size: 1.5rem; letter-spacing: 0.3em; font-family: monospace;"
                  required
                >
                <small class="form-text text-muted mt-2">
                  <i data-lucide="info" class="me-1"></i>
                  Digite o código de 6 dígitos que você recebeu por email
                </small>
              </div>
              
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                  <i data-lucide="check" class="me-2"></i>
                  Verificar Código
                </button>
              </div>
            </form>
            
            <div class="text-center mt-4">
              <p class="text-muted mb-2">Não recebeu o código?</p>
              <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link p-0 text-decoration-none">
                  <i data-lucide="refresh-cw" class="me-1"></i>
                  Reenviar código
                </button>
              </form>
            </div>
            
            <div class="text-center mt-3">
              <a href="{{ route('register') }}" class="text-muted text-decoration-none">
                <i data-lucide="arrow-left" class="me-1"></i>
                Voltar ao cadastro
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('plugin-styles')
{{-- Estilos movidos para custom.css --}}
@endpush

@push('custom-scripts')
<script>
// Auto-focus no campo de código
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar ícones Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    const codeInput = document.getElementById('verification_code');
    codeInput.focus();
    
    // Permitir apenas números
    codeInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Auto-submit quando 6 dígitos forem inseridos
        if (this.value.length === 6) {
            // Pequeno delay para melhor UX
            setTimeout(() => {
                this.form.submit();
            }, 500);
        }
    });
    
    // Permitir colar código
    codeInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const numbers = paste.replace(/[^0-9]/g, '').substring(0, 6);
        this.value = numbers;
        
        if (numbers.length === 6) {
            setTimeout(() => {
                this.form.submit();
            }, 500);
        }
    });
});
</script>
@endpush
@endsection
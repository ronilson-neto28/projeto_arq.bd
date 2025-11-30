@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><i data-lucide="home" class="me-1"></i>Formulário</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cadastrar Funcionário</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12">
    <!-- Header Card -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body bg-gradient header-adaptive" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <i data-lucide="user-plus" class="header-icon" style="width: 32px; height: 32px;"></i>
          </div>
          <div>
            <h4 class="card-title mb-1 header-title">Cadastro de Funcionário</h4>
            <p class="mb-0 header-subtitle">Preencha os dados pessoais e profissionais do funcionário</p>
          </div>
        </div>
      </div>

      <style>
        /*formulario-sombra*/ 
          .form-control {
            box-shadow: 0px 0px 2px 1px rgb(0 0 255 / 0.2) !important;
          }
          .form-control:focus {
            box-shadow: 1px 1px 2px 1px rgb(0 0 255 / 0.2) !important;
          }

          .form-check-input {
            box-shadow: 0px 0px 2px 1px rgb(0 0 255 / 0.2) !important;
          }

          .form-select {
            box-shadow: 0px 0px 2px 1px rgb(0 0 255 / 0.2) !important;
          }

          .form-select.js-select2 {
            box-shadow: 0px 0px 2px 1px rgb(0 0 255 / 0.2) !important;
          }
      </style>
      
      <style>
        /* Tema claro */
        [data-bs-theme="light"] .header-adaptive,
        .header-adaptive {
          color: #333 !important;
        }
        
        [data-bs-theme="light"] .header-title,
        .header-title {
          color: #333 !important;
        }
        
        [data-bs-theme="light"] .header-subtitle,
        .header-subtitle {
          color: rgba(51, 51, 51, 0.8) !important;
        }
        
        [data-bs-theme="light"] .header-icon,
        .header-icon {
          color: #333 !important;
        }
        
        /* Tema escuro */
        [data-bs-theme="dark"] .header-adaptive {
          color: white !important;
        }
        
        [data-bs-theme="dark"] .header-title {
          color: white !important;
        }
        
        [data-bs-theme="dark"] .header-subtitle {
          color: rgba(255, 255, 255, 0.9) !important;
        }
        
        [data-bs-theme="dark"] .header-icon {
          color: white !important;
        }
      </style>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center mb-2">
          <i data-lucide="alert-triangle" class="me-2 text-danger"></i>
          <strong>Atenção! Corrija os erros abaixo:</strong>
        </div>
        <ul class="mb-0 ps-4">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('funcionarios.store') }}" novalidate>
      @csrf

      <!-- Dados Pessoais Card -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light border-0">
          <div class="d-flex align-items-center">
            <i data-lucide="user" class="me-2 text-primary"></i>
            <h5 class="mb-0 text-primary fw-semibold">Dados Pessoais</h5>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nome" class="form-label"><i data-lucide="user" class="me-1"></i>Nome <span class="text-danger">*</span></label>
              <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome') }}" required>
            </div>
            <div class="col-md-3">
              <label for="cpf" class="form-label"><i data-lucide="credit-card" class="me-1"></i>CPF <span class="text-danger">*</span></label>
              <input type="text" id="cpf" name="cpf" class="form-control" value="{{ old('cpf') }}" placeholder="000.000.000-00" required>
            </div>
            <div class="col-md-3">
              <label for="rg" class="form-label"><i data-lucide="id-card" class="me-1"></i>RG</label>
              <input type="text" id="rg" name="rg" class="form-control" value="{{ old('rg') }}">
            </div>

            <div class="col-md-3">
              <label for="data_nascimento" class="form-label"><i data-lucide="calendar" class="me-1"></i>Nascimento</label>
              <input type="text" id="data_nascimento" name="data_nascimento" class="form-control js-date" value="{{ old('data_nascimento') }}" placeholder="dd/mm/aaaa">
            </div>
            <div class="col-md-3">
              <label class="form-label d-block"><i data-lucide="users" class="me-1"></i>Gênero</label>
              <div class="d-flex gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="genero" id="generoM" value="M" @checked(old('genero')=='M')>
                  <label class="form-check-label" for="generoM">Masculino</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="genero" id="generoF" value="F" @checked(old('genero')=='F')>
                  <label class="form-check-label" for="generoF">Feminino</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="genero" id="generoO" value="O" @checked(old('genero')=='O')>
                  <label class="form-check-label" for="generoO">Outro</label>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <label for="estado_civil" class="form-label"><i data-lucide="heart" class="me-1"></i>Estado Civil</label>
              <select id="estado_civil" name="estado_civil" class="form-select">
                <option value="">Selecione...</option>
                @foreach(['solteiro(a)','casado(a)','divorciado(a)','viúvo(a)'] as $ec)
                  <option value="{{ $ec }}" @selected(old('estado_civil')===$ec)>{{ $ec }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label for="telefone" class="form-label"><i data-lucide="phone" class="me-1"></i>Telefone</label>
              <input type="text" id="telefone" name="telefone" class="form-control" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label"><i data-lucide="mail" class="me-1"></i>E-mail</label>
              <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@dominio.com.br">
            </div>
          </div>
        </div>
      </div>

      <!-- Dados Profissionais Card -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light border-0">
          <div class="d-flex align-items-center">
            <i data-lucide="briefcase" class="me-2 text-success"></i>
            <h5 class="mb-0 text-success fw-semibold">Dados Profissionais</h5>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="empresa_id" class="form-label"><i data-lucide="building" class="me-1"></i>Empresa <span class="text-danger">*</span></label>
              <select id="empresa_id" name="empresa_id" class="form-select js-select2" data-width="100%" data-placeholder="Selecione a empresa" required>
                <option></option>
                @isset($empresas)
                  @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}" @selected(old('empresa_id') == $empresa->id)>{{ $empresa->razao_social }}</option>
                  @endforeach
                @endisset
              </select>
            </div>

            <div class="col-md-6">
              <label for="cargo_id" class="form-label"><i data-lucide="briefcase" class="me-1"></i>Cargo</label>
              <select id="cargo_id" name="cargo_id" class="form-select js-select2" data-width="100%" data-placeholder="Selecione o cargo">
                <option></option>
                @isset($cargos)
                  @foreach($cargos as $cargo)
                    <option value="{{ $cargo->id }}" @selected(old('cargo_id') == $cargo->id)>
                      CBO {{ $cargo->cbo }} - {{ $cargo->descricao ?? $cargo->nome }}
                    </option>
                  @endforeach
                @endisset
              </select>
              <small class="text-muted"><i data-lucide="info" class="me-1" style="width: 12px; height: 12px;"></i>Cargos são independentes de empresa.</small>
            </div>

            <div class="col-md-3">
              <label for="data_admissao" class="form-label"><i data-lucide="calendar-plus" class="me-1"></i>Data de Admissão</label>
              <input type="text" id="data_admissao" name="data_admissao" class="form-control js-date" value="{{ old('data_admissao') }}" placeholder="dd/mm/aaaa">
            </div>
            <div class="col-md-5">
              <label for="setor" class="form-label"><i data-lucide="map-pin" class="me-1"></i>Setor / Lotação</label>
              <input type="text" id="setor" name="setor" class="form-control" value="{{ old('setor') }}">
            </div>
            <div class="col-md-4">
              <label for="turno" class="form-label"><i data-lucide="clock" class="me-1"></i>Turno</label>
              <select id="turno" name="turno" class="form-select">
                <option value="">Selecione...</option>
                @foreach(['Diurno','Noturno','Misto','Revezamento'] as $t)
                  <option value="{{ $t }}" @selected(old('turno')===$t)>{{ $t }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Botões de Ação -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light">
          <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
              <i data-lucide="info" class="me-1"></i>
              <small>Campos marcados com <span class="text-danger">*</span> são obrigatórios</small>
            </div>
            <div class="d-flex gap-2">
              <a href="{{ route('funcionarios.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="x" class="me-1"></i>Cancelar
              </a>
              <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="me-1"></i>Salvar Funcionário
              </button>
            </div>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>
@endsection

@push('custom-scripts')
@vite(['resources/js/pages/inputmask.js', 'resources/js/pages/select2.js', 'resources/js/pages/flatpickr.js'])
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Aguardar o jQuery e Select2 estarem disponíveis
  function waitForLibraries() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
      initializeComponents();
    } else {
      setTimeout(waitForLibraries, 100);
    }
  }
  
  function initializeComponents() {
    const $ = jQuery;
    
    $('.js-select2').select2({ 
      allowClear: true, 
      placeholder: function(){ 
        return $(this).data('placeholder') || 'Selecione...'; 
      } 
    });
    
    $('.js-date').flatpickr({ 
      dateFormat: 'd/m/Y', 
      allowInput: true 
    });

    // cargos independentes: nenhuma filtragem por empresa
  }
  
  waitForLibraries();
  
  // Função para capitalizar primeira letra e letras após espaços
  function capitalizeNames(str) {
    return str.toLowerCase().replace(/\b\w/g, function(char) {
      return char.toUpperCase();
    });
  }

  // Aplicar formatação ao campo nome
  const nomeInput = document.getElementById('nome');
  if (nomeInput) {
    nomeInput.addEventListener('input', function(e) {
      const cursorPosition = e.target.selectionStart;
      const formattedValue = capitalizeNames(e.target.value);
      e.target.value = formattedValue;
      
      // Manter posição do cursor
      e.target.setSelectionRange(cursorPosition, cursorPosition);
    });
  }
});
</script>
@endpush

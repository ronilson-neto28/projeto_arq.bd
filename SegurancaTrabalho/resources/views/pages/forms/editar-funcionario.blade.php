@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><i data-lucide="home" class="me-1"></i>Formulário</a></li>
    <li class="breadcrumb-item"><a href="{{ route('funcionarios.index') }}">Funcionários</a></li>
    <li class="breadcrumb-item active" aria-current="page">Editar Funcionário</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12">
    <!-- Header Card -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body bg-gradient header-adaptive" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <i data-lucide="user-edit" class="header-icon" style="width: 32px; height: 32px;"></i>
          </div>
          <div>
            <h4 class="card-title mb-1 header-title">Editar Funcionário</h4>
            <p class="mb-0 header-subtitle">Atualize os dados pessoais e profissionais do funcionário</p>
          </div>
        </div>
      </div>
      
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
          color: #fff !important;
        }
        
        [data-bs-theme="dark"] .header-title {
          color: #fff !important;
        }
        
        [data-bs-theme="dark"] .header-subtitle {
          color: rgba(255, 255, 255, 0.8) !important;
        }
        
        [data-bs-theme="dark"] .header-icon {
          color: #fff !important;
        }
      </style>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
      <div class="card-body">
        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i data-lucide="alert-circle" class="me-2" style="width: 16px; height: 16px;"></i>
            <strong>Erro!</strong> Verifique os campos abaixo:
            <ul class="mb-0 mt-2">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <form action="{{ route('funcionarios.update', $funcionario->id) }}" method="POST" id="formFuncionario">
          @csrf
          @method('PUT')
          
          <!-- Dados da Empresa e Cargo -->
          <div class="row mb-4">
            <div class="col-12">
              <h6 class="text-muted mb-3"><i data-lucide="building" class="me-2" style="width: 16px; height: 16px;"></i>Dados Profissionais</h6>
            </div>
            
            <div class="col-md-6">
              <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
              <select class="form-select" id="empresa_id" name="empresa_id" required>
                <option value="">Selecione uma empresa</option>
                @foreach($empresas as $empresa)
                  <option value="{{ $empresa->id }}" {{ old('empresa_id', $funcionario->empresa_id) == $empresa->id ? 'selected' : '' }}>
                    {{ $empresa->razao_social ?: $empresa->nome_fantasia }}
                  </option>
                @endforeach
              </select>
              @error('empresa_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6">
              <label for="cargo_id" class="form-label">Cargo <span class="text-danger">*</span></label>
              <select class="form-select" id="cargo_id" name="cargo_id" required>
                <option value="">Selecione um cargo</option>
                @foreach($cargos as $cargo)
                  <option value="{{ $cargo->id }}" data-empresa-id="{{ $cargo->empresa_id }}" {{ old('cargo_id', $funcionario->cargo_id) == $cargo->id ? 'selected' : '' }}>
                    {{ $cargo->nome }}
                  </option>
                @endforeach
              </select>
              @error('cargo_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Dados Pessoais -->
          <div class="row mb-4">
            <div class="col-12">
              <h6 class="text-muted mb-3"><i data-lucide="user" class="me-2" style="width: 16px; height: 16px;"></i>Dados Pessoais</h6>
            </div>
            
            <div class="col-md-6">
              <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $funcionario->nome) }}" required maxlength="255">
              @error('nome')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-3">
              <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf', $funcionario->cpf) }}" required placeholder="000.000.000-00">
              @error('cpf')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-3">
              <label for="rg" class="form-label">RG</label>
              <input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg', $funcionario->rg) }}" maxlength="50">
              @error('rg')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-4">
              <label for="data_nascimento" class="form-label">Data de Nascimento</label>
              <input type="text" class="form-control" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento', $funcionario->data_nascimento ? \Carbon\Carbon::parse($funcionario->data_nascimento)->format('d/m/Y') : '') }}" placeholder="dd/mm/aaaa">
              @error('data_nascimento')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-4">
              <label for="genero" class="form-label">Gênero</label>
              <select class="form-select" id="genero" name="genero">
                <option value="">Selecione</option>
                <option value="Masculino" {{ old('genero', $funcionario->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                <option value="Feminino" {{ old('genero', $funcionario->genero) == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                <option value="Outro" {{ old('genero', $funcionario->genero) == 'Outro' ? 'selected' : '' }}>Outro</option>
              </select>
              @error('genero')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-4">
              <label for="estado_civil" class="form-label">Estado Civil</label>
              <select class="form-select" id="estado_civil" name="estado_civil">
                <option value="">Selecione</option>
                <option value="Solteiro" {{ old('estado_civil', $funcionario->estado_civil) == 'Solteiro' ? 'selected' : '' }}>Solteiro(a)</option>
                <option value="Casado" {{ old('estado_civil', $funcionario->estado_civil) == 'Casado' ? 'selected' : '' }}>Casado(a)</option>
                <option value="Divorciado" {{ old('estado_civil', $funcionario->estado_civil) == 'Divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                <option value="Viúvo" {{ old('estado_civil', $funcionario->estado_civil) == 'Viúvo' ? 'selected' : '' }}>Viúvo(a)</option>
              </select>
              @error('estado_civil')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Dados de Contato -->
          <div class="row mb-4">
            <div class="col-12">
              <h6 class="text-muted mb-3"><i data-lucide="mail" class="me-2" style="width: 16px; height: 16px;"></i>Dados de Contato</h6>
            </div>
            
            <div class="col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $funcionario->email) }}" maxlength="255">
              @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6">
              <label for="data_admissao" class="form-label">Data de Admissão</label>
              <input type="text" class="form-control" id="data_admissao" name="data_admissao" value="{{ old('data_admissao', $funcionario->data_admissao ? \Carbon\Carbon::parse($funcionario->data_admissao)->format('d/m/Y') : '') }}" placeholder="dd/mm/aaaa">
              @error('data_admissao')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Botões -->
          <div class="row">
            <div class="col-12">
              <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('funcionarios.index') }}" class="btn btn-light">
                  <i data-lucide="arrow-left" class="me-1" style="width: 16px; height: 16px;"></i>Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                  <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>Atualizar Funcionário
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('build/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('build/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('build/plugins/flatpickr/l10n/pt.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
(function(){
  // Máscaras
  const cpfInput = document.getElementById('cpf');
  const nascInput = document.getElementById('data_nascimento');
  const admInput = document.getElementById('data_admissao');

  function aplicarMascaraCPF(input) {
    input.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
      }
    });
  }

  function aplicarMascaraData(input) {
    input.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length <= 8) {
        value = value.replace(/(\d{2})(\d)/, '$1/$2');
        value = value.replace(/(\d{2})(\d)/, '$1/$2');
        e.target.value = value;
      }
    });
  }

  if (cpfInput) aplicarMascaraCPF(cpfInput);
  if (nascInput) aplicarMascaraData(nascInput);
  if (admInput) aplicarMascaraData(admInput);

  // Filtrar cargos por empresa
  const empresaSelect = document.getElementById('empresa_id');
  const cargoSelect = document.getElementById('cargo_id');
  const todasOpcoesCargo = Array.from(cargoSelect.options);

  function filtrarCargos() {
    const empresaId = empresaSelect.value;
    
    // Limpar opções atuais (exceto a primeira)
    cargoSelect.innerHTML = '<option value="">Selecione um cargo</option>';
    
    if (empresaId) {
      // Adicionar apenas cargos da empresa selecionada
      todasOpcoesCargo.forEach(option => {
        if (option.value && option.dataset.empresaId === empresaId) {
          cargoSelect.appendChild(option.cloneNode(true));
        }
      });
    } else {
      // Se nenhuma empresa selecionada, mostrar todos os cargos
      todasOpcoesCargo.forEach(option => {
        if (option.value) {
          cargoSelect.appendChild(option.cloneNode(true));
        }
      });
    }
  }

  empresaSelect?.addEventListener('change', filtrarCargos);
  
  // Aplicar filtro inicial se já houver empresa selecionada
  if (empresaSelect?.value) {
    filtrarCargos();
    // Reselecionar o cargo se estava selecionado
    const cargoAtual = '{{ old("cargo_id", $funcionario->cargo_id) }}';
    if (cargoAtual) {
      cargoSelect.value = cargoAtual;
    }
  }
})();
</script>
@endpush
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<style>
  .header-adaptive {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
  }
  
  [data-bs-theme="light"] .header-title {
    color: white !important;
  }
  
  [data-bs-theme="light"] .header-subtitle {
    color: rgba(255, 255, 255, 0.9) !important;
  }
  
  [data-bs-theme="light"] .header-icon {
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

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Formulário</a></li>
    <li class="breadcrumb-item"><a href="{{ route('empresas.index') }}">Empresas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Editar Empresa</li>
  </ol>
</nav>

<div class="header-adaptive">
  <div class="d-flex align-items-center">
    <div class="me-3">
      <i data-lucide="edit" class="header-icon" style="width: 48px; height: 48px;"></i>
    </div>
    <div>
      <h2 class="header-title mb-1">Editar Empresa</h2>
      <p class="header-subtitle mb-0">Atualize os dados de identificação e contato da empresa</p>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('empresas.update', $empresa->id) }}" novalidate>
        @csrf
        @method('PUT')

        <!-- Dados de Identificação Card -->
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-light border-0">
            <div class="d-flex align-items-center">
              <i data-lucide="file-text" class="me-2 text-primary"></i>
              <h5 class="mb-0 text-primary fw-semibold">Dados de Identificação</h5>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-8">
                <label for="razao_social" class="form-label"><i data-lucide="building" class="me-1"></i>Razão Social <span class="text-danger">*</span></label>
                <input type="text" id="razao_social" name="razao_social" class="form-control" value="{{ old('razao_social', $empresa->razao_social) }}" required>
              </div>
              <div class="col-md-4">
                <label for="nome_fantasia" class="form-label"><i data-lucide="tag" class="me-1"></i>Nome Fantasia</label>
                <input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control" value="{{ old('nome_fantasia', $empresa->nome_fantasia) }}">
              </div>
              <div class="col-md-6">
                <label for="cnpj" class="form-label"><i data-lucide="file-text" class="me-1"></i>CNPJ <span class="text-danger">*</span></label>
                <input type="text" id="cnpj" name="cnpj" class="form-control" value="{{ old('cnpj', $empresa->cnpj) }}" data-inputmask="'mask': '99.999.999/9999-99'" required>
              </div>
              <div class="col-md-6">
                <label for="cnae_id" class="form-label"><i data-lucide="briefcase" class="me-1"></i>CNAE</label>
                <select id="cnae_id" name="cnae_id" class="form-select select2" data-width="100%">
                  <option value="">Selecione um CNAE</option>
                  @foreach($cnaes ?? [] as $cnae)
                    <option value="{{ $cnae->id }}" {{ old('cnae_id', $empresa->cnae_id) == $cnae->id ? 'selected' : '' }}>
                      {{ $cnae->codigo }} - {{ $cnae->descricao }} (Risco: {{ $cnae->grau_risco }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label for="grau_risco" class="form-label"><i data-lucide="alert-triangle" class="me-1"></i>Grau de Risco</label>
                <select id="grau_risco" name="grau_risco" class="form-select">
                  <option value="">Automático via CNAE</option>
                  <option value="1" {{ old('grau_risco', $empresa->grau_risco) == '1' ? 'selected' : '' }}>1 - Baixo</option>
                  <option value="2" {{ old('grau_risco', $empresa->grau_risco) == '2' ? 'selected' : '' }}>2 - Médio</option>
                  <option value="3" {{ old('grau_risco', $empresa->grau_risco) == '3' ? 'selected' : '' }}>3 - Alto</option>
                  <option value="4" {{ old('grau_risco', $empresa->grau_risco) == '4' ? 'selected' : '' }}>4 - Crítico</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Dados de Contato Card -->
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-light border-0">
            <div class="d-flex align-items-center">
              <i data-lucide="map-pin" class="me-2 text-success"></i>
              <h5 class="mb-0 text-success fw-semibold">Dados de Contato</h5>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-3">
                <label for="cep" class="form-label"><i data-lucide="navigation" class="me-1"></i>CEP</label>
                <input type="text" id="cep" name="cep" class="form-control" value="{{ old('cep', $empresa->cep) }}" data-inputmask="'mask': '99999-999'" placeholder="00000-000">
              </div>
              <div class="col-md-5">
                <label for="endereco" class="form-label"><i data-lucide="map" class="me-1"></i>Endereço</label>
                <input type="text" id="endereco" name="endereco" class="form-control" value="{{ old('endereco', $empresa->endereco) }}" placeholder="Rua, número">
              </div>
              <div class="col-md-4">
                <label for="bairro" class="form-label"><i data-lucide="home" class="me-1"></i>Bairro</label>
                <input type="text" id="bairro" name="bairro" class="form-control" value="{{ old('bairro', $empresa->bairro) }}">
              </div>
              <div class="col-md-5">
                <label for="cidade" class="form-label"><i data-lucide="map-pin" class="me-1"></i>Cidade</label>
                <input type="text" id="cidade" name="cidade" class="form-control" value="{{ old('cidade', $empresa->cidade) }}">
              </div>
              <div class="col-md-2">
                <label for="uf" class="form-label"><i data-lucide="flag" class="me-1"></i>UF</label>
                <input type="text" id="uf" name="uf" class="form-control" maxlength="2" value="{{ old('uf', $empresa->uf) }}" placeholder="UF">
              </div>
              <div class="col-md-5">
                <label for="email" class="form-label"><i data-lucide="mail" class="me-1"></i>E-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $empresa->email) }}" placeholder="contato@empresa.com.br">
              </div>
              <div class="col-md-4">
                <label for="telefone" class="form-label"><i data-lucide="phone" class="me-1"></i>Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="{{ old('telefone', $empresa->telefones->first()->numero ?? '') }}" placeholder="(00) 00000-0000">
              </div>
            </div>
          </div>
        </div>

        <!-- Botões de Ação Card -->
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted"><i data-lucide="info" class="me-1" style="width: 14px; height: 14px;"></i>Campos marcados com <span class="text-danger">*</span> são obrigatórios</small>
              <div>
                <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary me-2">
                  <i data-lucide="x" class="me-1" style="width: 16px; height: 16px;"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                  <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>Atualizar
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('build/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('build/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
  @vite(['resources/js/pages/inputmask.js', 'resources/js/pages/select2.js'])
@endpush
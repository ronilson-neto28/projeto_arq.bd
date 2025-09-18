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
    <li class="breadcrumb-item active" aria-current="page">Cadastrar Empresa</li>
  </ol>
</nav>

<!-- Header com Gradiente -->
<div class="header-adaptive">
  <div class="d-flex align-items-center">
    <i data-lucide="building-2" class="header-icon me-3" style="width: 48px; height: 48px;"></i>
    <div>
      <h4 class="header-title mb-1 fw-bold">CADASTRO DE EMPRESA</h4>
      <p class="header-subtitle mb-0">Preencha os dados de identificação e contato da empresa</p>
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

      <form method="POST" action="{{ route('empresas.store') }}" novalidate>
        @csrf

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
                <input type="text" id="razao_social" name="razao_social" class="form-control" value="{{ old('razao_social') }}" required>
              </div>
              <div class="col-md-4">
                <label for="cnpj" class="form-label"><i data-lucide="hash" class="me-1"></i>CNPJ <span class="text-danger">*</span></label>
                <input type="text" id="cnpj" name="cnpj" class="form-control" value="{{ old('cnpj') }}" placeholder="00.000.000/0000-00" required>
              </div>

              <div class="col-md-6">
                <label for="nome_fantasia" class="form-label"><i data-lucide="tag" class="me-1"></i>Nome Fantasia</label>
                <input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control" value="{{ old('nome_fantasia') }}">
              </div>

              <div class="col-md-6">
                <label for="cnae_id" class="form-label"><i data-lucide="briefcase" class="me-1"></i>CNAE Principal</label>
                <select id="cnae_id" name="cnae_id" class="form-select js-select2" data-width="100%" data-placeholder="Selecione o CNAE">
                  <option></option>
                  @isset($cnaes)
                    @foreach($cnaes as $cnae)
                      <option value="{{ $cnae->id }}"
                        data-grau="{{ $cnae->grau_risco }}"
                        @selected(old('cnae_id') == $cnae->id)>
                        {{ $cnae->codigo }} — {{ $cnae->descricao }}
                      </option>
                    @endforeach
                  @endisset
                </select>
              </div>

              <div class="col-md-3">
                <label for="grau_risco" class="form-label"><i data-lucide="alert-triangle" class="me-1"></i>Grau de Risco (NR-4)</label>
                <input type="number" min="1" max="4" id="grau_risco" name="grau_risco" class="form-control" value="{{ old('grau_risco') }}" placeholder="1..4">
                <small class="text-muted">Preenche pelo CNAE; pode ajustar.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Dados de Endereço e Contato Card -->
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-light border-0">
            <div class="d-flex align-items-center">
              <i data-lucide="map-pin" class="me-2 text-success"></i>
              <h5 class="mb-0 text-success fw-semibold">Endereço e Contato</h5>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-2">
                <label for="cep" class="form-label"><i data-lucide="navigation" class="me-1"></i>CEP</label>
                <input type="text" id="cep" name="cep" class="form-control" value="{{ old('cep') }}" placeholder="00000-000">
              </div>
              <div class="col-md-6">
                <label for="endereco" class="form-label"><i data-lucide="map" class="me-1"></i>Endereço</label>
                <input type="text" id="endereco" name="endereco" class="form-control" value="{{ old('endereco') }}" placeholder="Rua, número">
              </div>
              <div class="col-md-4">
                <label for="bairro" class="form-label"><i data-lucide="home" class="me-1"></i>Bairro</label>
                <input type="text" id="bairro" name="bairro" class="form-control" value="{{ old('bairro') }}">
              </div>
              <div class="col-md-5">
                <label for="cidade" class="form-label"><i data-lucide="map-pin" class="me-1"></i>Cidade</label>
                <input type="text" id="cidade" name="cidade" class="form-control" value="{{ old('cidade') }}">
              </div>
              <div class="col-md-2">
                <label for="uf" class="form-label"><i data-lucide="flag" class="me-1"></i>UF</label>
                <input type="text" id="uf" name="uf" class="form-control" maxlength="2" value="{{ old('uf') }}" placeholder="UF">
              </div>
              <div class="col-md-5">
                <label for="email" class="form-label"><i data-lucide="mail" class="me-1"></i>E-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contato@empresa.com.br">
              </div>
              <div class="col-md-4">
                <label for="telefone" class="form-label"><i data-lucide="phone" class="me-1"></i>Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
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
                  <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>Salvar
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
  <script src="{{ asset('js/cnae-grau-risco.js') }}"></script>
  <script>
    (function($) {
      // Inicializar Select2
      $('.js-select2').select2({
        allowClear: true,
        placeholder: function() {
          return $(this).data('placeholder') || 'Selecione...';
        }
      });
      
      // Função para atualizar grau de risco
      function atualizarGrauRiscoInline() {
        const selectedOption = $('#cnae_id option:selected');
        const grauRisco = selectedOption.attr('data-grau');
        
        console.log('Inline: Opção selecionada:', selectedOption.text());
        console.log('Inline: Grau de risco:', grauRisco);
        
        if (grauRisco) {
          $('#grau_risco').val(grauRisco);
          console.log('Inline: Grau atualizado para:', grauRisco);
        } else {
          $('#grau_risco').val('');
        }
      }
      
      // Configurar eventos do Select2 imediatamente após inicialização
      $('#cnae_id').on('select2:select', function(e) {
        console.log('Inline Select2: select event triggered');
        setTimeout(atualizarGrauRiscoInline, 50);
      });
      
      $('#cnae_id').on('select2:clear', function(e) {
        console.log('Inline Select2: clear event triggered');
        $('#grau_risco').val('');
      });
      
      // Função para capitalizar primeira letra e letras após espaços
      function capitalizeNames(str) {
        return str.toLowerCase().replace(/\b\w/g, function(char) {
          return char.toUpperCase();
        });
      }

      // Aplicar formatação ao campo nome fantasia
      const nomeFantasiaInput = document.getElementById('nome_fantasia');
      if (nomeFantasiaInput) {
        nomeFantasiaInput.addEventListener('input', function(e) {
          const cursorPosition = e.target.selectionStart;
          const formattedValue = capitalizeNames(e.target.value);
          e.target.value = formattedValue;
          
          // Manter posição do cursor
          e.target.setSelectionRange(cursorPosition, cursorPosition);
        });
      }
      
      // Executar uma vez para valor inicial
      setTimeout(atualizarGrauRiscoInline, 100);
      
    })(jQuery);
  </script>
@endpush

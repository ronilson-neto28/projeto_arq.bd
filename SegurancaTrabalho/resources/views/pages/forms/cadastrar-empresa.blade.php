@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Formulário</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cadastrar Empresa</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3">Cadastro de Empresa</h4>

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

          {{-- Identificação --}}
          <div class="row g-3">
            <div class="col-md-8">
              <label for="razao_social" class="form-label">Razão Social <span class="text-danger">*</span></label>
              <input type="text" id="razao_social" name="razao_social" class="form-control" value="{{ old('razao_social') }}" required>
            </div>
            <div class="col-md-4">
              <label for="cnpj" class="form-label">CNPJ <span class="text-danger">*</span></label>
              <input type="text" id="cnpj" name="cnpj" class="form-control" value="{{ old('cnpj') }}" placeholder="00.000.000/0000-00" required>
            </div>

            <div class="col-md-6">
              <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
              <input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control" value="{{ old('nome_fantasia') }}">
            </div>

            <div class="col-md-6">
              <label for="cnae_id" class="form-label">CNAE Principal</label>
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
              <label for="grau_risco" class="form-label">Grau de Risco (NR-4)</label>
              <input type="number" min="1" max="4" id="grau_risco" name="grau_risco" class="form-control" value="{{ old('grau_risco') }}" placeholder="1..4">
              <small class="text-muted">Preenche pelo CNAE; pode ajustar.</small>
            </div>
          </div>

          <hr class="my-4">

          {{-- Endereço / Contato --}}
          <div class="row g-3">
            <div class="col-md-2">
              <label for="cep" class="form-label">CEP</label>
              <input type="text" id="cep" name="cep" class="form-control" value="{{ old('cep') }}" placeholder="00000-000">
            </div>
            <div class="col-md-6">
              <label for="endereco" class="form-label">Endereço</label>
              <input type="text" id="endereco" name="endereco" class="form-control" value="{{ old('endereco') }}" placeholder="Rua, número">
            </div>
            <div class="col-md-4">
              <label for="bairro" class="form-label">Bairro</label>
              <input type="text" id="bairro" name="bairro" class="form-control" value="{{ old('bairro') }}">
            </div>
            <div class="col-md-5">
              <label for="cidade" class="form-label">Cidade</label>
              <input type="text" id="cidade" name="cidade" class="form-control" value="{{ old('cidade') }}">
            </div>
            <div class="col-md-2">
              <label for="uf" class="form-label">UF</label>
              <input type="text" id="uf" name="uf" class="form-control" maxlength="2" value="{{ old('uf') }}" placeholder="UF">
            </div>
            <div class="col-md-5">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contato@empresa.com.br">
            </div>
            <div class="col-md-4">
              <label for="telefone" class="form-label">Telefone</label>
              <input type="text" id="telefone" name="telefone" class="form-control" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
            </div>
          </div>

          <hr class="my-4">

          {{-- Responsáveis (PCMSO / PGR) --}}
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Médico Coordenador do PCMSO</label>
              <input type="text" name="medico_pcmso_nome" class="form-control mb-2" value="{{ old('medico_pcmso_nome') }}" placeholder="Nome completo">
              <div class="row g-2">
                <div class="col-md-5">
                  <input type="text" name="medico_pcmso_crm" class="form-control" value="{{ old('medico_pcmso_crm') }}" placeholder="CRM">
                </div>
                <div class="col-md-2">
                  <input type="text" name="medico_pcmso_uf" class="form-control" value="{{ old('medico_pcmso_uf') }}" placeholder="UF">
                </div>
                <div class="col-md-5">
                  <input type="email" name="medico_pcmso_email" class="form-control" value="{{ old('medico_pcmso_email') }}" placeholder="E-mail (opcional)">
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Resp. PGR / SST</label>
              <input type="text" name="resp_sst_nome" class="form-control mb-2" value="{{ old('resp_sst_nome') }}" placeholder="Nome completo">
              <div class="row g-2">
                <div class="col-md-6">
                  <input type="text" name="resp_sst_registro" class="form-control" value="{{ old('resp_sst_registro') }}" placeholder="CREA/COREN/CRP...">
                </div>
                <div class="col-md-6">
                  <input type="email" name="resp_sst_email" class="form-control" value="{{ old('resp_sst_email') }}" placeholder="E-mail (opcional)">
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
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
    })(jQuery);
  </script>
@endpush

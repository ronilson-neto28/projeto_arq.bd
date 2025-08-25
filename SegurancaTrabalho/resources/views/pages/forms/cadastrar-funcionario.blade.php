@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Formulário</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cadastrar Funcionário</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3">Cadastro de Funcionário</h4>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('funcionarios.store') }}" novalidate>
          @csrf

          {{-- Dados pessoais --}}
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
              <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome') }}" required>
            </div>
            <div class="col-md-3">
              <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
              <input type="text" id="cpf" name="cpf" class="form-control" value="{{ old('cpf') }}" placeholder="000.000.000-00" required>
            </div>
            <div class="col-md-3">
              <label for="rg" class="form-label">RG</label>
              <input type="text" id="rg" name="rg" class="form-control" value="{{ old('rg') }}">
            </div>

            <div class="col-md-3">
              <label for="data_nascimento" class="form-label">Nascimento</label>
              <input type="text" id="data_nascimento" name="data_nascimento" class="form-control js-date" value="{{ old('data_nascimento') }}" placeholder="dd/mm/aaaa">
            </div>
            <div class="col-md-3">
              <label class="form-label d-block">Gênero</label>
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
              <label for="estado_civil" class="form-label">Estado Civil</label>
              <select id="estado_civil" name="estado_civil" class="form-select">
                <option value="">Selecione...</option>
                @foreach(['solteiro(a)','casado(a)','divorciado(a)','viúvo(a)'] as $ec)
                  <option value="{{ $ec }}" @selected(old('estado_civil')===$ec)>{{ $ec }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label for="telefone" class="form-label">Telefone</label>
              <input type="text" id="telefone" name="telefone" class="form-control" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@dominio.com.br">
            </div>
          </div>

          <hr class="my-4">

          {{-- Vínculo / Lotação --}}
          <div class="row g-3">
            <div class="col-md-6">
              <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
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
              <label for="cargo_id" class="form-label">Cargo</label>
              <select id="cargo_id" name="cargo_id" class="form-select js-select2" data-width="100%" data-placeholder="Selecione o cargo">
                <option></option>
                @isset($cargos)
                  @foreach($cargos as $cargo)
                    <option value="{{ $cargo->id }}" data-empresa="{{ $cargo->empresa_id }}" @selected(old('cargo_id') == $cargo->id)>
                      {{ $cargo->descricao }} — {{ $cargo->empresa->razao_social ?? 'Empresa' }}
                    </option>
                  @endforeach
                @endisset
              </select>
              <small class="text-muted">Após escolher a empresa, o combo filtra os cargos dela.</small>
            </div>

            <div class="col-md-3">
              <label for="data_admissao" class="form-label">Data de Admissão</label>
              <input type="text" id="data_admissao" name="data_admissao" class="form-control js-date" value="{{ old('data_admissao') }}" placeholder="dd/mm/aaaa">
            </div>
            <div class="col-md-5">
              <label for="setor" class="form-label">Setor / Lotação</label>
              <input type="text" id="setor" name="setor" class="form-control" value="{{ old('setor') }}">
            </div>
            <div class="col-md-4">
              <label for="turno" class="form-label">Turno</label>
              <select id="turno" name="turno" class="form-select">
                <option value="">Selecione...</option>
                @foreach(['Diurno','Noturno','Misto','Revezamento'] as $t)
                  <option value="{{ $t }}" @selected(old('turno')===$t)>{{ $t }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('funcionarios.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
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
<script>
(function($){
  $('.js-select2').select2({ allowClear:true, placeholder: function(){ return $(this).data('placeholder') || 'Selecione...'; } });
  $('.js-date').flatpickr({ dateFormat: 'd/m/Y', allowInput:true });

  function filtrarCargos() {
    const empId = $('#empresa_id').val();
    $('#cargo_id option').each(function(){
      const optEmp = $(this).data('empresa');
      if (!$(this).val()) return;
      $(this).toggle(!empId || String(optEmp) === String(empId));
    });
    const sel = $('#cargo_id').val();
    if (sel) {
      const belongs = $('#cargo_id option:selected').data('empresa');
      if (String(belongs) !== String(empId)) {
        $('#cargo_id').val(null).trigger('change');
      }
    }
  }

  $('#empresa_id').on('change', filtrarCargos);
  filtrarCargos();
})(jQuery);
</script>
@endpush

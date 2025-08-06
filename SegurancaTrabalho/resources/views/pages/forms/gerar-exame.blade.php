@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb bg-light p-2 rounded">
    <li class="breadcrumb-item"><a href="#">Formulário</a></li>
    <li class="breadcrumb-item active" aria-current="page">Gerar Exame</li>
  </ol>
</nav>

<div class="card shadow-sm">
  <div class="card-body">
    <h4 class="card-title mb-4 text-primary fw-semibold">Criar Guia de Encaminhamento</h4>

    <form method="POST" action="#">
      @csrf

      <div class="row mb-4">
        <div class="col-md-6">
          <label class="form-label fw-medium">Funcionário da Empresa</label>
          <select class="form-control select2">
            <option selected>Funcionário 1</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-medium">Empresa / Cargo</label>
          <select class="form-control select2">
            <option selected>Empresa Exemplo / Analista Administrativo</option>
          </select>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-4">
          <label class="form-label fw-medium">Tipo</label>
          <select class="form-control">
            <option>Admissional</option>
            <option selected>Periódico</option>
            <option>Demissional</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-medium">Data do Documento</label>
          <input type="text" class="form-control flatpickr" value="{{ date('d/m/Y') }}">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-medium">Modo</label>
          <select class="form-control">
            <option selected>Rascunho (Editar)</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-medium">Link Público</label>
          <select class="form-control">
            <option selected>Não</option>
            <option>Sim</option>
          </select>
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-medium">Exames e Procedimentos</label>
        <select class="form-control select2" multiple>
          <option selected>Audiometria Tonal Ocupacional</option>
          <option selected>Avaliação Clínica Ocupacional</option>
          <option selected>Glicemia</option>
        </select>
      </div>

      <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Procedimento</th>
              <th>Data</th>
              <th>Hora</th>
              <th>Prestador</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>[Audiometria] Audiometria Tonal Ocupacional</td>
              <td><input type="text" class="form-control flatpickr"></td>
              <td><input type="time" class="form-control"></td>
              <td>
                <select class="form-control select2">
                  <option selected>Clínica</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>[Clínica] Avaliação Clínica Ocupacional</td>
              <td><input type="text" class="form-control flatpickr"></td>
              <td><input type="time" class="form-control"></td>
              <td>
                <select class="form-control select2">
                  <option selected>Clínica</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>[Laboratório] Glicemia</td>
              <td><input type="text" class="form-control flatpickr"></td>
              <td><input type="time" class="form-control"></td>
              <td>
                <select class="form-control select2">
                  <option selected>Laboratório</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-end">
        <a href="#" class="btn btn-outline-secondary me-2">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('build/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('build/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script>
    $(document).ready(function () {
      $('.select2').select2({
        width: '100%'
      });
      $('.flatpickr').flatpickr({
        dateFormat: "d/m/Y"
      });
    });
  </script>
@endpush
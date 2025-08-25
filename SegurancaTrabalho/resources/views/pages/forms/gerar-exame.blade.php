@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <style>
    .heading-gradient{
      background: linear-gradient(90deg,#3b0764 0%, #0ea5a7 100%);
      color:#fff; border-radius:.6rem; padding:.75rem 1rem; font-weight:700;
      letter-spacing:.5px; text-transform:uppercase; text-align:center; margin-bottom:1rem;
    }
    .select2-container--default .select2-selection--single{
      min-height:44px; border-radius:.6rem; border:1px solid var(--bs-border-color,#dee2e6);
      display:flex; align-items:center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{ padding-left:.75rem; }
    .btn-risk { margin: .2rem .35rem .2rem 0; }
    .table-actions small a{ text-decoration:none; font-weight:700; margin-left:.5rem; }
  </style>
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
    <div class="heading-gradient">Criar Guia de Encaminhamento</div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('encaminhamentos.store') }}">
      @csrf

      <input type="hidden" id="empresa_id" name="empresa_id" value="{{ old('empresa_id') }}">

      {{-- Identificação da guia --}}
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Número da Guia</label>
          <input type="text" name="numero_guia" class="form-control" value="{{ old('numero_guia') }}" placeholder="Opcional (gera automático se vazio)">
        </div>
        <div class="col-md-4">
          <label class="form-label">Data de Emissão</label>
          <input type="text" name="data_emissao" class="form-control flatpickr" value="{{ old('data_emissao', date('d/m/Y')) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Médico Solicitante</label>
          <input type="text" name="medico_solicitante" class="form-control" value="{{ old('medico_solicitante') }}" placeholder="Nome — CRM/UF">
        </div>
      </div>

      {{-- Funcionário / Empresa-Cargo --}}
      <div class="row mb-4">
        <div class="col-md-6">
          <label for="selFuncionario" class="form-label fw-medium">Nome — Funcionário</label>
          <select id="selFuncionario" name="funcionario_id" class="form-control select2-single" data-placeholder="Selecione o funcionário" required>
            <option></option>
            @isset($funcionarios)
              @foreach($funcionarios as $f)
                <option value="{{ $f->id }}"
                  data-empresa-id="{{ $f->empresa_id }}"
                  data-cargo-id="{{ $f->cargo_id }}">
                  {{ $f->nome }} — {{ $f->empresa->razao_social ?? 'Empresa' }} {{ $f->cargo ? '/ '.$f->cargo->descricao : '' }}
                </option>
              @endforeach
            @endisset
          </select>
        </div>

        <div class="col-md-6">
          <label for="selEmpresaCargo" class="form-label fw-medium">Empresa / Cargo</label>
          <select id="selEmpresaCargo" name="cargo_id" class="form-control select2-single" data-placeholder="Selecione a empresa/cargo">
            <option></option>
            @isset($cargos)
              @foreach($cargos as $c)
                <option value="{{ $c->id }}" data-empresa-id="{{ $c->empresa_id }}">
                  {{ $c->empresa->razao_social ?? 'Empresa' }} / {{ $c->descricao }}
                </option>
              @endforeach
            @endisset
          </select>
          <small class="text-muted">Ao escolher o cargo, o <b>empresa_id</b> é preenchido automaticamente.</small>
        </div>
      </div>

      {{-- Tipo (Exame) / Data / Hora / Previsão de retorno --}}
      <div class="row mb-4">
        <div class="col-md-3">
          <label for="selTipo" class="form-label fw-medium">Tipo (Exame)</label>
          <select id="selTipo" name="tipo_exame" class="form-control select2-single" required>
            @php $tipos = ['Admissional','Periódico','Demissional','Retorno','Mudança de Função']; @endphp
            @foreach($tipos as $t)
              <option value="{{ $t }}" @selected(old('tipo_exame') === $t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-medium">Data do Atendimento</label>
          <div class="input-group">
            <input id="atendimentoData" name="data_atendimento" type="text" class="form-control flatpickr"
                   value="{{ old('data_atendimento', date('d/m/Y')) }}" placeholder="dd/mm/aaaa">
            <span class="input-group-text"><i data-lucide="calendar"></i></span>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-medium">Hora</label>
          <input id="atendimentoHora" name="hora_atendimento" type="time" class="form-control" step="60" value="{{ old('hora_atendimento') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-medium">Previsão Retorno</label>
          <input type="text" name="previsao_retorno" class="form-control flatpickr" value="{{ old('previsao_retorno') }}" placeholder="dd/mm/aaaa">
        </div>
      </div>

      {{-- Observações --}}
      <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between">
          <label for="obs" class="form-label fw-medium mb-1">Observações</label>
          <a href="javascript:void(0)" id="puxarHistorico" class="small fw-bold">[PUXAR DO HISTÓRICO]</a>
        </div>
        <textarea id="obs" name="observacoes" class="form-control" rows="2" placeholder="Observações adicionais...">{{ old('observacoes') }}</textarea>
      </div>

      {{-- Riscos Ocupacionais --}}
      <div class="mb-4">
        <label class="form-label fw-medium">Riscos Ocupacionais</label>
        <div id="riscosWrap" class="d-flex flex-wrap">
          @php
            $riscosPadrao = ['Ruído','Calor','Vibração','Radiações','Químicos','Biológicos','Ergonômicos','Acidentes'];
          @endphp
          @foreach($riscosPadrao as $i => $risco)
            @php $id = 'risco_'.\Illuminate\Support\Str::slug($risco,'_'); @endphp
            <input type="checkbox" class="btn-check" id="{{ $id }}" autocomplete="off" name="riscos[]" value="{{ $risco }}"
                   @checked(collect(old('riscos',[]))->contains($risco))>
            <label class="btn btn-outline-secondary btn-sm btn-risk" for="{{ $id }}">{{ $risco }}</label>
          @endforeach
        </div>
        <div class="d-flex mt-2 gap-2">
          <input type="text" id="novoRisco" class="form-control" placeholder="Adicionar outro risco...">
          <button type="button" id="btnAddRisco" class="btn btn-outline-primary">Adicionar</button>
        </div>
      </div>

      {{-- Exames e Procedimentos --}}
      <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between">
          <label class="form-label fw-medium mb-1">Exames e Procedimentos</label>
          <a href="javascript:void(0)" id="puxarPcmso" class="small fw-bold">[PUXAR DO PCMSO]</a>
        </div>
        <div class="row g-2">
          <div class="col-md-9">
            <select id="procedimentoAdd" class="form-control select2-single" data-placeholder="Selecione um procedimento">
              <option></option>
              @isset($procedimentos)
                @foreach($procedimentos as $p)
                  <option value="{{ $p->nome }}"
                    data-prestador="{{ $p->prestador_padrao }}"
                    data-cat="{{ $p->categoria }}">
                    {{ $p->nome }}
                  </option>
                @endforeach
              @else
                <option value="Audiometria Tonal Ocupacional">Audiometria Tonal Ocupacional</option>
                <option value="Avaliação Clínica Ocupacional">Avaliação Clínica Ocupacional</option>
                <option value="Glicemia">Glicemia</option>
              @endisset
            </select>
          </div>
          <div class="col-md-3 d-grid">
            <button type="button" id="btnAddProc" class="btn btn-primary">Adicionar</button>
          </div>
        </div>
        <small class="text-muted">Use o seletor acima para incluir itens 1-a-1 na tabela.</small>
      </div>

      {{-- Tabela de procedimentos --}}
      <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Procedimento</th>
              <th class="table-actions">
                DATA
                <small>
                  <a href="javascript:void(0)" id="limparTodasDatas" class="text-danger">[X]</a>
                  <a href="javascript:void(0)" id="datasHoje" class="text-primary">[HOJE]</a>
                </small>
              </th>
              <th>HORA <small class="text-muted">(Brasília GMT-3)</small></th>
              <th>Prestador</th>
            </tr>
          </thead>
          <tbody id="tbodyProcedimentos">
            @php $oldProcs = old('procedimentos', []); @endphp
            @if(!empty($oldProcs))
              @foreach($oldProcs as $idx => $it)
                <tr data-proc="{{ $it['procedimento'] }}">
                  <td>
                    {{ $it['procedimento'] }}
                    <input type="hidden" name="procedimentos[{{ $idx }}][procedimento]" value="{{ $it['procedimento'] }}">
                  </td>
                  <td>
                    <div class="input-group">
                      <input name="procedimentos[{{ $idx }}][data]" type="text" class="form-control flatpickr-proc" placeholder="dd/mm/aaaa" value="{{ $it['data'] ?? '' }}">
                      <button type="button" class="btn btn-outline-secondary btn-sm btn-clear-date" title="Limpar">X</button>
                      <button type="button" class="btn btn-outline-primary btn-sm btn-hoje" title="Hoje">Hoje</button>
                    </div>
                  </td>
                    <td>
                      <input name="procedimentos[{{ $idx }}][hora]" type="time" class="form-control input-hora" step="60" value="{{ $it['hora'] ?? '' }}">
                    </td>
                    <td>
                      <select name="procedimentos[{{ $idx }}][prestador]" class="form-control select2-single prestador-select">
                        @php $pv = $it['prestador'] ?? 'Clínica'; @endphp
                        <option @selected($pv==='Clínica')>Clínica</option>
                        <option @selected($pv==='Laboratório')>Laboratório</option>
                        <option @selected($pv==='Audiometria')>Audiometria</option>
                        <option @selected($pv==='Imagem')>Imagem</option>
                      </select>
                    </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-end">
        <a href="{{ route('forms.exames.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
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
(function($){
  function todayBR(){
    const d=new Date(), dd=String(d.getDate()).padStart(2,'0'), mm=String(d.getMonth()+1).padStart(2,'0'), yy=d.getFullYear();
    return `${dd}/${mm}/${yy}`;
  }

  function initPlugins(){
    $('.select2-single').select2({ width:'100%', allowClear:true, placeholder: function(){ return $(this).data('placeholder') || 'Selecione...'; } });
    $('.flatpickr').flatpickr({ dateFormat: "d/m/Y" });
    rebindRowPlugins();
  }

  function rebindRowPlugins(){
    $('.flatpickr-proc').each(function(){ this._flatpickr?.destroy(); });
    $('.flatpickr-proc').flatpickr({ dateFormat: "d/m/Y" });
    $('#tbodyProcedimentos .prestador-select').select2({ width:'100%' });
  }

  function rowExists(procName){
    return document.querySelector(`#tbodyProcedimentos tr[data-proc="${CSS.escape(procName)}"]`) !== null;
  }

  function addProc(procName, prestadorDefault){
    if(!procName) return;
    if(rowExists(procName)) return;
    const idx = document.querySelectorAll('#tbodyProcedimentos tr').length;
    const safeName = $('<div>').text(procName).html();
    const prest = prestadorDefault || 'Clínica';

    const $row = $(`
      <tr data-proc="${safeName}">
        <td>
          ${safeName}
          <input type="hidden" name="procedimentos[${idx}][procedimento]" value="${safeName}">
        </td>
        <td>
          <div class="input-group">
            <input name="procedimentos[${idx}][data]" type="text" class="form-control flatpickr-proc" placeholder="dd/mm/aaaa">
            <button type="button" class="btn btn-outline-secondary btn-sm btn-clear-date" title="Limpar">X</button>
            <button type="button" class="btn btn-outline-primary btn-sm btn-hoje" title="Hoje">Hoje</button>
          </div>
        </td>
        <td>
          <input name="procedimentos[${idx}][hora]" type="time" class="form-control input-hora" step="60">
        </td>
        <td>
          <select name="procedimentos[${idx}][prestador]" class="form-control select2-single prestador-select">
            <option ${prest==='Clínica' ? 'selected':''}>Clínica</option>
            <option ${prest==='Laboratório' ? 'selected':''}>Laboratório</option>
            <option ${prest==='Audiometria' ? 'selected':''}>Audiometria</option>
            <option ${prest==='Imagem' ? 'selected':''}>Imagem</option>
          </select>
        </td>
      </tr>
    `);
    $('#tbodyProcedimentos').append($row);
    rebindRowPlugins();

    const dTop = $('#atendimentoData').val();
    const hTop = $('#atendimentoHora').val();
    if (dTop) $row.find('.flatpickr-proc').val(dTop);
    if (hTop) $row.find('.input-hora').val(hTop);
  }

  // ações por linha
  $(document).on('click', '.btn-clear-date', function(){
    $(this).closest('.input-group').find('input[type="text"]').val('');
  });
  $(document).on('click', '.btn-hoje', function(){
    $(this).closest('.input-group').find('input[type="text"]').val(todayBR()).trigger('change');
  });

  // ações de cabeçalho
  $('#limparTodasDatas').on('click', function(){ $('.flatpickr-proc').val(''); });
  $('#datasHoje').on('click', function(){ $('.flatpickr-proc').val(todayBR()).trigger('change'); });

  // propagar data/hora
  $('#atendimentoData').on('change', function(){
    const v = $(this).val();
    $('#tbodyProcedimentos .flatpickr-proc').each(function(){ if(!this.value) this.value = v; });
  });
  $('#atendimentoHora').on('change', function(){
    const v = $(this).val();
    $('#tbodyProcedimentos .input-hora').each(function(){ if(!this.value) this.value = v; });
  });

  // adicionar procedimento
  $('#btnAddProc').on('click', function(){
    const $opt = $('#procedimentoAdd').find('option:selected');
    const name = $opt.val();
    const prest = $opt.data('prestador') || 'Clínica';
    addProc(name, prest);
    $('#procedimentoAdd').val(null).trigger('change');
  });
  $('#procedimentoAdd').on('select2:select', function(e){
    const name = e.params.data.id;
    const $opt = $(this).find(`option[value="${name}"]`);
    addProc(name, $opt.data('prestador') || 'Clínica');
    $(this).val(null).trigger('change');
  });

  // histórico (exemplo)
  $('#puxarHistorico').on('click', function(){
    const texto = 'Observação anterior: acompanhamento sem restrições.';
    const $ta = $('#obs');
    $ta.val(($ta.val()? $ta.val()+'\n' : '') + texto).focus();
  });

  // riscos personalizados
  $('#btnAddRisco').on('click', function(){
    const txt = ($('#novoRisco').val() || '').trim();
    if(!txt) return;
    const id = 'risco_' + txt.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'');
    if(document.getElementById(id)) {
      const el = document.getElementById(id);
      el.checked = true;
      $('#novoRisco').val('');
      return;
    }
    const safe = $('<div>').text(txt).html();
    const $input = $(`<input type="checkbox" class="btn-check" id="${id}" autocomplete="off" name="riscos[]" value="${safe}">`);
    const $label = $(`<label class="btn btn-outline-secondary btn-sm btn-risk" for="${id}">${safe}</label>`);
    $('#riscosWrap').append($input, $label);
    $input.prop('checked', true);
    $('#novoRisco').val('');
  });

  // sincronizar empresa_id
  $('#selEmpresaCargo').on('change', function(){
    const empId = $(this).find('option:selected').data('empresa-id') || '';
    $('#empresa_id').val(empId);
  });
  $('#selFuncionario').on('change', function(){
    const empId = $(this).find('option:selected').data('empresa-id') || '';
    const cargoId = $(this).find('option:selected').data('cargo-id') || '';
    if(empId) $('#empresa_id').val(empId);
    if(cargoId) $('#selEmpresaCargo').val(cargoId).trigger('change');
  });

  $(function(){ initPlugins(); });
})(jQuery);
</script>
@endpush

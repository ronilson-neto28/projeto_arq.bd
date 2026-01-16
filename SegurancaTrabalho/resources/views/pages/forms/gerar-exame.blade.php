@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <style>
    .select2-container--default .select2-selection--single{
      min-height:44px; border-radius:.6rem; border:1px solid var(--bs-border-color,#dee2e6);
      display:flex; align-items:center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{ padding-left:.75rem; }
    .btn-risk { margin: .2rem .35rem .2rem 0; }
    .table-actions small a{ text-decoration:none; font-weight:700; margin-left:.5rem; }
    .btn-check:checked + .btn-risk{ color:#fff; border-color:transparent; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); box-shadow:0 4px 14px rgba(102,126,234,.35); }
    #examesObrigatoriosLista .exams-card{ border:1px dashed #e9ecef; border-radius:12px; padding:.75rem 1rem; background:#fafbff; }
    #examesObrigatoriosLista .exams-card ul{ margin:.5rem 0 0; padding:0; list-style:none; }
    #examesObrigatoriosLista .exams-card li{ padding:.35rem .5rem; border-radius:8px; display:flex; align-items:center; gap:.5rem; }
    #examesObrigatoriosLista .badge{ border-radius:6px; }
    .form-section{ border:1px solid var(--bs-border-color,#e9ecef); border-radius:12px; overflow:hidden; margin-bottom:1rem; box-shadow:0 1px 6px rgba(0,0,0,.04); }
    .form-section-header{ display:flex; align-items:center; gap:.5rem; padding:.75rem 1rem; background:#f5f6fa; border-bottom:1px solid var(--bs-border-color,#e9ecef); }
    .form-section-header .title{ font-weight:600; color:#4b5563; }
    .form-section-body{ padding:1rem; }
  </style>
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><i data-lucide="home" class="me-1"></i>Formulário</a></li>
    <li class="breadcrumb-item"><a href="{{ route('forms.exames.index') }}">Exames</a></li>
    <li class="breadcrumb-item active" aria-current="page">Gerar Exame</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12">
    <!-- Header Card -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body bg-gradient header-adaptive" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <i data-lucide="clipboard-plus" class="header-icon" style="width: 32px; height: 32px;"></i>
          </div>
          <div>
            <h4 class="card-title mb-1 header-title">Gerar Encaminhamento para Exame</h4>
            <p class="mb-0 header-subtitle">Crie uma nova guia de encaminhamento ocupacional</p>
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
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('forms.exames.store') }}" id="formGerarExame">
      @csrf

      

      <div class="form-section">
        <div class="form-section-header">
          <i data-lucide="user"></i>
          <span class="title">Dados do Funcionário</span>
        </div>
        <div class="form-section-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="selFuncionario" class="form-label fw-medium">Nome — Funcionário</label>
              <select id="selFuncionario" name="funcionario_id" class="form-control select2-single" data-placeholder="Selecione o funcionário" required>
                <option></option>
                @isset($funcionarios)
                  @foreach($funcionarios as $f)
                    <option value="{{ $f->id }}"
                      data-empresa-id="{{ $f->empresa_id }}"
                      data-cargo-id="{{ $f->cargo_id }}"
                      data-cpf="{{ $f->cpf }}"
                      data-telefone="{{ optional($f->telefones->first())->numero }}">
                      {{ $f->nome }}
                    </option>
                  @endforeach
                @endisset
              </select>
            </div>
            <div class="col-md-6">
              <label for="selEmpresa" class="form-label fw-medium">Empresa</label>
              <select id="selEmpresa" name="empresa_id" class="form-control select2-single" data-placeholder="Selecione a empresa" required>
                <option></option>
                @isset($empresas)
                  @foreach($empresas as $e)
                    <option value="{{ $e->id }}">{{ $e->razao_social ?? ($e->nome_fantasia ?? '') }}</option>
                  @endforeach
                @endisset
              </select>
            </div>
            <div class="col-md-6">
              <label for="selCargo" class="form-label fw-medium">Cargo</label>
              <select id="selCargo" name="cargo_id" class="form-control select2-single" data-placeholder="Selecione o cargo">
                <option></option>
                @isset($cargos)
                  @foreach($cargos as $c)
                    <option value="{{ $c->id }}">{{ $c->nome }}</option>
                  @endforeach
                @endisset
              </select>
            </div>
            <div class="col-md-3">
              <label for="funcionario_cpf" class="form-label">CPF</label>
              <input type="text" id="funcionario_cpf" class="form-control" placeholder="000.000.000-00">
            </div>
            <div class="col-md-3">
              <label for="funcionario_telefone" class="form-label">Telefone</label>
              <input type="text" id="funcionario_telefone" class="form-control" placeholder="(00) 00000-0000">
            </div>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-header">
          <i data-lucide="clipboard-list"></i>
          <span class="title">Dados do Exame</span>
        </div>
        <div class="form-section-body">
          <div class="row g-3">
            <div class="col-md-3">
              <label for="selTipo" class="form-label fw-medium">Tipo (Exame)</label>
              <select id="selTipo" name="tipo_exame" class="form-control select2-single" required>
                @php $tipos = ['Admissional','Periódico','Demissional','Retorno','Mudança de Função']; @endphp
                @foreach($tipos as $t)
                  <option value="{{ $t }}" @selected(old('tipo_exame') === $t)>{{ $t }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-5">
              <label for="data_atendimento" class="form-label">Data e Hora do Atendimento <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="text" id="data_atendimento" name="data_atendimento" class="form-control js-date" placeholder="dd/mm/aaaa" value="{{ old('data_atendimento', date('d/m/Y')) }}" required>
                <input type="time" id="hora_atendimento" name="hora_atendimento" class="form-control" value="{{ old('hora_atendimento','08:00') }}" required>
              </div>
            </div>
            <div class="col-md-3">
              <label for="previsao_retorno" class="form-label">Previsão Retorno</label>
              <input type="text" id="previsao_retorno" name="previsao_retorno" class="form-control js-date" placeholder="dd/mm/aaaa" value="{{ old('previsao_retorno') }}">
            </div>
            <div class="col-md-4">
              <label for="status" class="form-label fw-medium">Status</label>
              <select id="status" name="status" class="form-control select2-single" required>
                @php $statusOptions = ['agendado','realizado','faltou','cancelado']; @endphp
                @foreach($statusOptions as $s)
                  <option value="{{ $s }}" @selected(old('status', 'agendado') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label for="local_clinica_id" class="form-label fw-medium">Local/Clínica (Opcional)</label>
              <input type="text" id="local_clinica_id" name="local_clinica_id" class="form-control" value="{{ old('local_clinica_id') }}" placeholder="Nome da clínica ou local">
            </div>
            <div class="col-md-4">
              <label for="medico_responsavel_id" class="form-label fw-medium">Médico Responsável (Opcional)</label>
              <input type="text" id="medico_responsavel_id" name="medico_responsavel_id" class="form-control" value="{{ old('medico_responsavel_id') }}" placeholder="Nome do médico responsável">
            </div>
            <div class="col-12">
              <div class="d-flex align-items-center justify-content-between">
                <label for="obs" class="form-label fw-medium mb-1">Observações Gerais</label>
                <a href="javascript:void(0)" id="puxarHistorico" class="small fw-bold text-primary">[PUXAR DO HISTÓRICO]</a>
              </div>
              <textarea id="obs" name="observacoes" class="form-control" rows="3" placeholder="Observações adicionais sobre o exame...">{{ old('observacoes') }}</textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-header">
          <i data-lucide="alert-triangle"></i>
          <span class="title">Riscos Ocupacionais</span>
        </div>
        <div class="form-section-body">
      <div id="riscosWrap">
        <label class="form-label fw-medium">Riscos Identificados</label>
        <h6 class="mt-3 mb-2 text-secondary border-bottom">1. RISCOS FÍSICOS</h6>
        <div class="d-flex gap-2 mb-2">
          <input type="text" id="novoRiscoFisico" class="form-control" placeholder="adicionar risco fisico">
          <button type="button" id="btnAddRiscoFisico" class="btn btn-outline-primary">Adicionar</button>
        </div>
        <div class="d-flex flex-wrap" id="listaRiscoFisico"></div>
        <h6 class="mt-3 mb-2 text-secondary border-bottom">2. RISCOS QUÍMICOS</h6>
        <div class="d-flex gap-2 mb-2">
          <input type="text" id="novoRiscoQuimico" class="form-control" placeholder="adicionar risco quimico">
          <button type="button" id="btnAddRiscoQuimico" class="btn btn-outline-primary">Adicionar</button>
        </div>
        <div class="d-flex flex-wrap" id="listaRiscoQuimico"></div>
        <h6 class="mt-3 mb-2 text-secondary border-bottom">3. BIOLÓGICOS</h6>
        <div class="d-flex gap-2 mb-2">
          <input type="text" id="novoRiscoBiologico" class="form-control" placeholder="adicionar risco biologico">
          <button type="button" id="btnAddRiscoBiologico" class="btn btn-outline-primary">Adicionar</button>
        </div>
        <div class="d-flex flex-wrap" id="listaRiscoBiologico"></div>
        <h6 class="mt-3 mb-2 text-secondary border-bottom">4. ERGONÔMICOS</h6>
        <div class="d-flex gap-2 mb-2">
          <input type="text" id="novoRiscoErgonomico" class="form-control" placeholder="adicionar risco ergonomico">
          <button type="button" id="btnAddRiscoErgonomico" class="btn btn-outline-primary">Adicionar</button>
        </div>
        <div class="d-flex flex-wrap" id="listaRiscoErgonomico"></div>
        <h6 class="mt-3 mb-2 text-secondary border-bottom">5. ACIDENTES</h6>
        <div class="d-flex gap-2 mb-2">
          <input type="text" id="novoRiscoAcidente" class="form-control" placeholder="adicionar risco acidente">
          <button type="button" id="btnAddRiscoAcidente" class="btn btn-outline-primary">Adicionar</button>
        </div>
        <div class="d-flex flex-wrap" id="listaRiscoAcidente"></div>
      </div>
        <div class="mt-3">
          <div id="examesObrigatoriosLista" class="p-3 rounded-3 bg-success-subtle border border-success-subtle">
            <small class="text-success">Selecione os riscos ocupacionais acima para calcular a lista de exames obrigatórios.</small>
          </div>
        </div>
        </div>
      </div>

      <input type="hidden" id="exames_finais_json" name="exames_finais_json">

      <div class="form-section">
        <div class="form-section-header">
          <i data-lucide="stethoscope"></i>
          <span class="title">Exames e Procedimentos</span>
        </div>
        <div class="form-section-body">
      <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between">
          <label class="form-label fw-medium mb-1">Adicionar Procedimentos</label>
          <a href="javascript:void(0)" id="puxarPcmso" class="small fw-bold text-primary">[PUXAR DO PCMSO]</a>
        </div>
        <div class="row g-2">
          <div class="col-md-9">
            @if(isset($exames) && count($exames))
              <select id="procedimentoAdd" class="form-control select2-single" data-placeholder="Selecione um procedimento">
                <option></option>
                @foreach($exames as $p)
                  <option value="{{ $p->nome }}" data-prestador="Clínica" data-codigo="{{ $p->codigo }}">{{ $p->codigo ? $p->codigo.' - ' : '' }}{{ $p->nome }}</option>
                @endforeach
              </select>
            @else
              <input type="text" id="procedimentoTexto" class="form-control" placeholder="Digite o procedimento">
            @endif
          </div>
          <div class="col-md-3 d-grid">
            <button type="button" id="btnAddProc" class="btn btn-primary">Adicionar</button>
          </div>
        </div>
        <small class="text-muted">Selecione um procedimento da lista ou digite para incluir na tabela.</small>
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
        </div>
      </div>

      {{-- Botões de Ação --}}
      <div class="row">
        <div class="col-12">
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('forms.exames.index') }}" class="btn btn-light">
              <i data-lucide="arrow-left" class="me-1" style="width: 16px; height: 16px;"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>Gerar Encaminhamento
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
@endpush

@push('custom-scripts')
<script>
(function($){
  function onlyDigits(s){ return String(s||'').replace(/\D+/g,''); }
  function formatCPF(v){ const d=onlyDigits(v).slice(0,11); if(d.length<=3)return d; if(d.length<=6)return d.replace(/(\d{3})(\d+)/,'$1.$2'); if(d.length<=9)return d.replace(/(\d{3})(\d{3})(\d+)/,'$1.$2.$3'); return d.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/,'$1.$2.$3-$4'); }
  function formatPhone(v){ const d=onlyDigits(v).slice(0,11); if(d.length<=2)return d; if(d.length<=6)return d.replace(/(\d{2})(\d+)/,'($1) $2'); if(d.length<=10)return d.replace(/(\d{2})(\d{4})(\d+)/,'($1) $2-$3'); return d.replace(/(\d{2})(\d{5})(\d{4})/,'($1) $2-$3'); }
  function applyMasks(){
    const cpfEl = document.getElementById('funcionario_cpf');
    const telEl = document.getElementById('funcionario_telefone');
    if(!cpfEl || !telEl) return;
    if(window.Inputmask){
      window.Inputmask('999.999.999-99').mask(cpfEl);
      window.Inputmask('(99) 99999-9999').mask(telEl);
    } else {
      $('#funcionario_cpf').on('input', function(){ this.value = formatCPF(this.value); });
      $('#funcionario_telefone').on('input', function(){ this.value = formatPhone(this.value); });
    }
  }
  function todayBR(){
    const d=new Date(), dd=String(d.getDate()).padStart(2,'0'), mm=String(d.getMonth()+1).padStart(2,'0'), yy=d.getFullYear();
    return `${dd}/${mm}/${yy}`;
  }

  function initPlugins(){
    $('.select2-single').select2({ width:'100%', allowClear:true, placeholder: function(){ return $(this).data('placeholder') || 'Selecione...'; } });
    $('.flatpickr').flatpickr({ dateFormat: "d/m/Y" });
    rebindRowPlugins();
    applyMasks();
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
          <div class="d-flex gap-2">
            <select name="procedimentos[${idx}][prestador]" class="form-control select2-single prestador-select" style="min-width:140px">
              <option ${prest==='Clínica' ? 'selected':''}>Clínica</option>
              <option ${prest==='Laboratório' ? 'selected':''}>Laboratório</option>
              <option ${prest==='Audiometria' ? 'selected':''}>Audiometria</option>
              <option ${prest==='Imagem' ? 'selected':''}>Imagem</option>
            </select>
            <input name="procedimentos[${idx}][prestador_nome]" type="text" class="form-control prestador-nome" placeholder="Nome da clínica">
          </div>
        </td>
      </tr>
    `);
    $('#tbodyProcedimentos').append($row);
    rebindRowPlugins();

    const dTop = $('#data_atendimento').val();
    const hTop = $('#hora_atendimento').val();
    const clinicTop = $('#local_clinica_id').val();
    if (dTop) $row.find('.flatpickr-proc').val(dTop);
    if (hTop) $row.find('.input-hora').val(hTop);
    if (clinicTop) $row.find('.prestador-nome').val(clinicTop);
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
  $('#data_atendimento').on('change', function(){
    const v = $(this).val();
    $('#tbodyProcedimentos .flatpickr-proc').each(function(){ if(!this.value) this.value = v; });
  });
  $('#hora_atendimento').on('change', function(){
    const v = $(this).val();
    $('#tbodyProcedimentos .input-hora').each(function(){ if(!this.value) this.value = v; });
  });
  $('#local_clinica_id').on('input', function(){
    const v = $(this).val();
    $('#tbodyProcedimentos .prestador-nome').each(function(){ if(!this.value) this.value = v; });
  });

  // adicionar procedimento
  $('#btnAddProc').on('click', function(){
    const $select = $('#procedimentoAdd');
    if ($select.length) {
      const $opt = $select.find('option:selected');
      const name = ($opt.val() || '').trim();
      const prest = $opt.data('prestador') || 'Clínica';
      if(!name) return;
      addProc(name, prest);
      $select.val(null).trigger('change');
    } else {
      const name = ($('#procedimentoTexto').val() || '').trim();
      if(!name) return;
      addProc(name, 'Clínica');
      $('#procedimentoTexto').val('');
    }
  });
  $('#procedimentoTexto').on('keydown', function(e){
    if(e.key==='Enter'){ e.preventDefault(); $('#btnAddProc').click(); }
  });

  // histórico (exemplo)
  $('#puxarHistorico').on('click', function(){
    const texto = 'Observação anterior: acompanhamento sem restrições.';
    const $ta = $('#obs');
    $ta.val(($ta.val()? $ta.val()+'\n' : '') + texto).focus();
  });

  function addRisco(listSel, textSel){
    const txt = ($(textSel).val() || '').trim();
    if(!txt) return;
    const id = 'risco_' + txt.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'');
    if(document.getElementById(id)) {
      const el = document.getElementById(id);
      el.checked = true;
      $(textSel).val('');
      return;
    }
    const safe = $('<div>').text(txt).html();
    const $wrap = $(`<div class="risk-item d-inline-flex align-items-center me-2 mb-2"></div>`);
    let catKey = '';
    if (listSel.includes('Fisico')) catKey = 'fisico';
    else if (listSel.includes('Quimico')) catKey = 'quimico';
    else if (listSel.includes('Biologico')) catKey = 'biologico';
    else if (listSel.includes('Ergonomico')) catKey = 'ergonomico';
    else if (listSel.includes('Acidente')) catKey = 'acidentes';
    const $input = $(`<input type="checkbox" class="btn-check" id="${id}" autocomplete="off" name="${catKey}[]" value="${txt}" checked>`);
    const $label = $(`<label class="btn btn-outline-secondary btn-sm btn-risk" for="${id}">${txt}</label>`);
    const $edit = $(`<button type="button" class="btn btn-light btn-sm ms-1">Editar</button>`);
    const $del = $(`<button type="button" class="btn btn-outline-danger btn-sm ms-1">Excluir</button>`);
    $wrap.append($input, $label, $edit, $del);
    $(listSel).append($wrap);
    $del.on('click', function(){ $wrap.remove(); atualizarExamesPorRisco(); });
    $edit.on('click', function(){
      const current = $label.text().trim();
      const novo = (prompt('Editar risco', current) || '').trim();
      if(!novo) return;
      const newId = 'risco_' + novo.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'');
      $input.attr('id', newId).val(novo);
      $label.attr('for', newId).text(novo);
      atualizarExamesPorRisco();
    });
    $(textSel).val('');
    atualizarExamesPorRisco();
  }
  $('#btnAddRiscoFisico').on('click', function(){ addRisco('#listaRiscoFisico','#novoRiscoFisico'); });
  $('#btnAddRiscoQuimico').on('click', function(){ addRisco('#listaRiscoQuimico','#novoRiscoQuimico'); });
  $('#btnAddRiscoBiologico').on('click', function(){ addRisco('#listaRiscoBiologico','#novoRiscoBiologico'); });
  $('#btnAddRiscoErgonomico').on('click', function(){ addRisco('#listaRiscoErgonomico','#novoRiscoErgonomico'); });
  $('#btnAddRiscoAcidente').on('click', function(){ addRisco('#listaRiscoAcidente','#novoRiscoAcidente'); });

  $('#selFuncionario').on('change', function(){
    const empId = $(this).find('option:selected').data('empresa-id') || '';
    const cargoId = $(this).find('option:selected').data('cargo-id') || '';
    const cpf = $(this).find('option:selected').data('cpf') || '';
    const tel = $(this).find('option:selected').data('telefone') || '';
    if(empId) $('#selEmpresa').val(empId).trigger('change');
    if(cargoId) $('#selCargo').val(cargoId).trigger('change');
    if(cpf) $('#funcionario_cpf').val(window.Inputmask ? cpf : formatCPF(cpf));
    if(tel) $('#funcionario_telefone').val(window.Inputmask ? tel : formatPhone(tel));
    applyMasks();
  });

  $(function(){ initPlugins(); });
  // integração riscos → exames obrigatórios
  const form = document.getElementById('formGerarExame');
  const selFuncionario = $('#selFuncionario');
  const selCargo = $('#selCargo');
  const examesFinaisInput = document.getElementById('exames_finais_json');
  const examesListaDiv = document.getElementById('examesObrigatoriosLista');
  let listaExamesCalculados = [];

  function renderizarExames(resp){
    const exames = Array.isArray(resp.exames) ? resp.exames : (Array.isArray(resp) ? resp : []);
    let html = '<div class="exams-card">';
    html += '<div class="d-flex align-items-center gap-2"><i data-lucide="stethoscope"></i><span class="fw-semibold">Exames solicitados</span></div>';
    if (exames.length === 0) {
      html += '<div class="mt-2 text-muted">Nenhum exame obrigatório encontrado para os riscos selecionados.</div>';
    } else {
      html += '<ul>';
      exames.forEach(function(exame){
        const periodicidade = exame.periodicidade_meses ? exame.periodicidade_meses+' meses' : 'N/A';
        const adm = exame.obrigatorio_admissional ? '<span class="badge bg-primary-subtle text-primary">Admissional</span>' : '';
        const code = exame.codigo ? `<span class="badge bg-light text-dark">${exame.codigo}</span>` : '';
        const tipo = exame.tipo_procedimento ? `<span class="badge bg-info-subtle text-info">${exame.tipo_procedimento}</span>` : '';
        html += `<li>
          <i data-lucide="check-circle-2" class="text-success"></i>
          <strong>${exame.nome}</strong>
          <span class="ms-2 d-inline-flex gap-1">${code}${tipo}${adm}</span>
          <small class="ms-2 text-muted">Periodicidade: ${periodicidade}</small>
        </li>`;
      });
      html += '</ul>';
    }
    html += '</div>';
    examesListaDiv.innerHTML = html;
  }

  function atualizarExamesPorRisco(){
    const riscosSelecionados = [];
    $('#riscosWrap input[type="checkbox"]:checked').each(function(){ riscosSelecionados.push($(this).val()); });
    if (riscosSelecionados.length === 0) {
      examesListaDiv.innerHTML = '<div class="exams-card text-muted">Nenhum risco selecionado. Nenhuma obrigatoriedade de exame calculada.</div>';
      listaExamesCalculados = [];
      return;
    }
    $.ajax({
      url: '{{ url("api/risco/exames") }}',
      method: 'POST',
      contentType: 'application/json',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      data: JSON.stringify({ riscos: riscosSelecionados }),
      success: function(response){ listaExamesCalculados = response.exames || response; renderizarExames(response); },
      error: function(xhr){ console.error('Erro ao buscar exames:', xhr.responseText); examesListaDiv.innerHTML = '<div class="exams-card text-danger">Erro ao calcular exames.</div>'; }
    });
  }

  $('#selFuncionario').on('change', function(){ atualizarExamesPorRisco(); });
  $('#riscosWrap').on('change', 'input[type="checkbox"]', atualizarExamesPorRisco);
  form?.addEventListener('submit', function(){ examesFinaisInput.value = JSON.stringify(listaExamesCalculados||[]); });
  form?.addEventListener('submit', function(){
    const selEmp = $('#selEmpresa');
    if (!selEmp.val()) {
      const empFromFunc = $('#selFuncionario').find('option:selected').data('empresa-id') || '';
      if (empFromFunc) selEmp.val(empFromFunc).trigger('change');
    }
  });
  atualizarExamesPorRisco();
})(jQuery);
</script>
@endpush

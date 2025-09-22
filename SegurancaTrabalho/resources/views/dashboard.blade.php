@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('build/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Bem vindo ao Painel</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <div class="input-group flatpickr w-200px me-2 mb-2 mb-md-0" id="dashboardDate">
      <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i data-lucide="calendar" class="text-primary"></i></span>
      <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date" data-input>
    </div>
    <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-lucide="printer"></i>
      Print
    </button>
    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-lucide="download-cloud"></i>
      Baixar Exames
    </button>
  </div>
</div>

<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
          <div class="card-body text-white position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="d-flex align-items-center">
                <div class="icon-wrapper p-2 rounded-circle" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                  <i data-lucide="building-2" style="width: 24px; height: 24px; color: white;"></i>
                </div>
                <div class="ms-3">
                  <h6 class="card-title mb-0 text-white-50 fw-normal" style="font-size: 0.85rem;">TOTAL DE EMPRESAS</h6>
                  <p class="mb-0 text-white-50" style="font-size: 0.75rem;">CADASTRADAS</p>
                </div>
              </div>
              <div class="dropdown">
                <a type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="text-white-50">
                  <i class="icon-sm" data-lucide="more-horizontal"></i>
                </a>
                <div class="dropdown-menu shadow border-0" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span>Visualizar</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span>Editar</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span>Exportar</span></a>
                </div>
              </div>
            </div>
            <div class="d-flex align-items-end justify-content-between">
              <div>
                <h2 class="mb-0 fw-bold text-white" style="font-size: 2.5rem;">{{ number_format($totalEmpresas, 0, ',', '.') }}</h2>
                <p class="mb-0 text-white-50" style="font-size: 0.8rem;">empresas ativas</p>
              </div>
              <div class="position-absolute" style="right: -10px; bottom: -10px; opacity: 0.1;">
                <i data-lucide="building-2" style="width: 80px; height: 80px;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px;">
          <div class="card-body text-white position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="d-flex align-items-center">
                <div class="icon-wrapper p-2 rounded-circle" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                  <i data-lucide="users" style="width: 24px; height: 24px; color: white;"></i>
                </div>
                <div class="ms-3">
                  <h6 class="card-title mb-0 text-white-50 fw-normal" style="font-size: 0.85rem;">TOTAL DE FUNCIONÁRIOS</h6>
                  <p class="mb-0 text-white-50" style="font-size: 0.75rem;">CADASTRADOS</p>
                </div>
              </div>
              <div class="dropdown">
                <a type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="text-white-50">
                  <i class="icon-sm" data-lucide="more-horizontal"></i>
                </a>
                <div class="dropdown-menu shadow border-0" aria-labelledby="dropdownMenuButton1">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span>Visualizar</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span>Editar</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span>Exportar</span></a>
                </div>
              </div>
            </div>
            <div class="d-flex align-items-end justify-content-between">
              <div>
                <h2 class="mb-0 fw-bold text-white" style="font-size: 2.5rem;">{{ number_format($totalFuncionarios, 0, ',', '.') }}</h2>
                <p class="mb-0 text-white-50" style="font-size: 0.8rem;">funcionários ativos</p>
              </div>
              <div class="position-absolute" style="right: -10px; bottom: -10px; opacity: 0.1;">
                <i data-lucide="users" style="width: 80px; height: 80px;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 15px;">
          <div class="card-body text-dark position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="d-flex align-items-center">
                <div class="icon-wrapper p-2 rounded-circle" style="background: rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
                  <i data-lucide="clock" style="width: 24px; height: 24px; color: #d63384;"></i>
                </div>
                <div class="ms-3">
                  <h6 class="card-title mb-0 fw-normal" style="font-size: 0.85rem; color: #6c5b7b;">TOTAL DE EXAMES</h6>
                  <p class="mb-0" style="font-size: 0.75rem; color: #6c5b7b;">PENDENTES</p>
                </div>
              </div>
              <div class="dropdown">
                <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6c5b7b;">
                  <i class="icon-sm" data-lucide="more-horizontal"></i>
                </a>
                <div class="dropdown-menu shadow border-0" aria-labelledby="dropdownMenuButton2">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span>Visualizar</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span>Editar</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span>Exportar</span></a>
                </div>
              </div>
            </div>
            <div class="d-flex align-items-end justify-content-between">
              <div>
                <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; color: #d63384;">0</h2>
                <div class="d-flex align-items-center mt-1">
                  <p class="mb-0 ms-2" style="font-size: 0.8rem; color: #6c5b7b;">exames pendentes</p>
                </div>
              </div>
              <div class="position-absolute" style="right: -10px; bottom: -10px; opacity: 0.1;">
                <i data-lucide="clock" style="width: 80px; height: 80px; color: #d63384;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 15px;">
          <div class="card-body text-dark position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="d-flex align-items-center">
                <div class="icon-wrapper p-2 rounded-circle" style="background: rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
                  <i data-lucide="file-check" style="width: 24px; height: 24px; color: #198754;"></i>
                </div>
                <div class="ms-3">
                  <h6 class="card-title mb-0 fw-normal" style="font-size: 0.85rem; color: #2d5a27;">TOTAL DE EXAMES</h6>
                  <p class="mb-0" style="font-size: 0.75rem; color: #2d5a27;">CADASTRADOS</p>
                </div>
              </div>
              <div class="dropdown">
                <a type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #2d5a27;">
                  <i class="icon-sm" data-lucide="more-horizontal"></i>
                </a>
                <div class="dropdown-menu shadow border-0" aria-labelledby="dropdownMenuButton3">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span>Visualizar</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span>Editar</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span>Exportar</span></a>
                </div>
              </div>
            </div>
            <div class="d-flex align-items-end justify-content-between">
              <div>
                <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; color: #198754;">{{ number_format($totalEncaminhamentos, 0, ',', '.') }}</h2>
                <div class="d-flex align-items-center mt-1">
                  <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                    <i data-lucide="check-circle" class="icon-xs me-1"></i>
                    Cadastrados
                  </span>
                </div>
              </div>
              <div class="position-absolute" style="right: -10px; bottom: -10px; opacity: 0.1;">
                <i data-lucide="file-check" style="width: 80px; height: 80px; color: #198754;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

<div class="row">
  <div class="col-lg-7 col-xl-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Exames Mensais</h6>
          <div class="dropdown mb-2">
            <a type="button" id="dropdownMenuButton4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <p class="text-secondary">Exames são atividades relacionadas à exames de funcionários em um determinado período de tempo.</p>
        <div id="monthlySalesChart"></div>
      </div> 
    </div>
  </div>
  <div class="col-lg-5 col-xl-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline">
          <h6 class="card-title mb-0">Encaminhamentos por Tipo</h6>
          <div class="dropdown mb-2">
            <a type="button" id="dropdownMenuButton5" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton5">
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <div id="examesPieChart"></div>
        <div class="row mb-3 mt-3">
          @foreach($examesPorTipo as $exame)
          <div class="col-12 mb-3">
            @php
               $examTypeColors = [
                  'admissional' => '#28a745',
                  'periodico' => '#007bff', 
                  'demissional' => '#dc3545',
                  'retorno' => '#fd7e14',
                  'mudanca_de_funcao' => '#ffc107'
                ];
               $normalizedType = strtolower(str_replace(' ', '_', $exame->tipo_exame));
               $color = $examTypeColors[$normalizedType] ?? '#6c757d';
               $rgbColor = '';
               if ($color === '#28a745') $rgbColor = '40, 167, 69';
                elseif ($color === '#007bff') $rgbColor = '0, 123, 255';
                elseif ($color === '#dc3545') $rgbColor = '220, 53, 69';
                elseif ($color === '#fd7e14') $rgbColor = '253, 126, 20';
                elseif ($color === '#ffc107') $rgbColor = '255, 193, 7';
                else $rgbColor = '108, 117, 125';
             @endphp
             <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: linear-gradient(135deg, rgba({{ $rgbColor }}, 0.1) 0%, rgba({{ $rgbColor }}, 0.05) 100%); border: 1px solid rgba({{ $rgbColor }}, 0.2);">
                <label class="d-flex align-items-center fs-11px text-uppercase fw-bold mb-0" style="color: #495057;">
                  <span class="me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background: {{ $color }}; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></span>
                {{ ucfirst(str_replace('_', ' ', $exame->tipo_exame)) }}
              </label>
              <div class="d-flex align-items-center">
                <span class="fw-bold me-2" style="color: #495057; font-size: 14px;">{{ $exame->total }}</span>
                <small class="text-muted" style="font-size: 10px;">encaminhamentos</small>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

<div class="row">
  <div class="col-lg-5 col-xl-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline">
          <h6 class="card-title mb-0">Exames</h6>
          <div class="dropdown mb-2">
            <a type="button" id="dropdownMenuButton5" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton5">
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <div id="storageChart2"></div>
        <div class="row mb-3">
          <div class="col-6 d-flex justify-content-end">
            <div>
              <label class="d-flex align-items-center justify-content-end fs-10px text-uppercase fw-bolder">Exames Concluidos <span class="p-1 ms-1 rounded-circle bg-secondary"></span></label>
              <!--<h5 class="fw-bolder mb-0 text-end">test</h5>-->
            </div>
          </div>
          <div class="col-6">
            <div>
              <label class="d-flex align-items-center fs-10px text-uppercase fw-bolder"><span class="p-1 me-1 rounded-circle bg-primary"></span> Exames Pendentes</label>
              <!--<h5 class="fw-bolder mb-0">~5TB</h5>-->
            </div>
          </div>
        </div>
        <!--<div class="d-grid">
          <button class="btn btn-primary">Upgrade storage</button>
        </div>-->
      </div>
    </div>
  </div>
  <div class="col-lg-7 col-xl-8 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Lista de Exames</h6>
          <div class="dropdown mb-2">
            <a type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span>View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span>Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="trash" class="icon-sm me-2"></i> <span>Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span>Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span>Download</span></a>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th class="pt-0">ID</th>
                <th class="pt-0">Nome</th>
                <th class="pt-0">Data Inicial</th>
                <th class="pt-0">Data Final</th>
                <th class="pt-0">Status</th>
                <th class="pt-0">Empresa</th>
                <th class="pt-0"></th> {{-- coluna do botão --}}
              </tr>
            </thead>
            <tbody id="listaExamesTbody">
              @foreach ($funcionarios as $funcionario)
                <tr class="exame-row">
                  <td>{{ $funcionario->id }}</td>
                  <td>{{ $funcionario->nome }}</td>
                  <td></td> <!-- Data Inicial -->
                  <td></td> <!-- Data Final -->
                  <td>
                    @php
                      $status = collect([
                        ['label' => 'Vencido', 'class' => 'bg-danger'],
                        ['label' => 'No Prazo', 'class' => 'bg-success'],
                        ['label' => 'Work in Progress', 'class' => 'bg-warning'],
                        ['label' => 'Coming soon', 'class' => 'bg-primary'],
                        ['label' => 'Pending', 'class' => 'bg-info'],
                      ])->random();
                    @endphp
                    <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                  </td>
                  <td>{{ $funcionario->empresa->nome ?? '—' }}</td>
                  <td>
                    <a href="{{ url('/forms/gerar-exame') }}" class="btn btn-primary">Gerar Exame</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Botão Mostrar mais/menos --}}
        @if($funcionarios->count() > 5)
          <div class="d-flex justify-content-center mt-3">
            <button
              type="button"
              id="btnMostrarMaisExames"
              class="btn btn-outline-secondary btn-sm"
              aria-expanded="false"
            >
              Mostrar mais
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>

@push('custom-scripts')
<script>
(function() {
  const LIMIT = 5;
  const tbody = document.getElementById('listaExamesTbody');
  if (!tbody) return;

  const rows = Array.from(tbody.querySelectorAll('.exame-row'));
  const btn   = document.getElementById('btnMostrarMaisExames');

  // Aplica limite inicial
  function applyLimit() {
    rows.forEach((tr, i) => { tr.style.display = (i < LIMIT) ? '' : 'none'; });
  }

  // Se não há mais que LIMIT, oculta botão e sai
  if (rows.length <= LIMIT) {
    if (btn) btn.classList.add('d-none');
    return;
  }

  applyLimit();

  // Toggle mostrar mais/menos
  btn?.addEventListener('click', function() {
    const expanded = this.getAttribute('aria-expanded') === 'true';
    if (expanded) {
      applyLimit();
      this.textContent = 'Mostrar mais';
      this.setAttribute('aria-expanded', 'false');
    } else {
      rows.forEach(tr => tr.style.display = '');
      this.textContent = 'Mostrar menos';
      this.setAttribute('aria-expanded', 'true');
    }
  });
})();
</script>
@endpush

</div> <!-- row -->

<!-- Nova seção para gráfico de encaminhamentos por mês -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-4">
          <h6 class="card-title mb-0">Encaminhamentos por Mês</h6>
          <div class="d-flex align-items-center">
            <select class="form-select form-select-sm me-2" id="anoSelecionado" style="width: auto;">
              @foreach($anosDisponiveis as $ano)
                <option value="{{ $ano }}" {{ $ano == $anosDisponiveis[0] ? 'selected' : '' }}>{{ $ano }}</option>
              @endforeach
            </select>
            <div class="dropdown">
              <a type="button" id="dropdownMenuButtonEncaminhamentos" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEncaminhamentos">
                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span>Visualizar</span></a>
                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span>Exportar</span></a>
                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span>Imprimir</span></a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12">
            <div id="encaminhamentosPorMesChart" style="height: 400px;"></div>
          </div>
        </div>
        
        <div class="row mt-3">
          <div class="col-md-3">
            <div class="d-flex align-items-center">
              <div class="me-2">
                <span class="badge bg-primary">Total</span>
              </div>
              <div>
                <h6 class="mb-0" id="totalEncaminhamentosAno">1,234</h6>
                <small class="text-muted">encaminhamentos no ano</small>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex align-items-center">
              <div class="me-2">
                <span class="badge bg-success">Média</span>
              </div>
              <div>
                <h6 class="mb-0" id="mediaEncaminhamentosMes">103</h6>
                <small class="text-muted">por mês</small>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex align-items-center">
              <div class="me-2">
                <span class="badge bg-warning">Pico</span>
              </div>
              <div>
                <h6 class="mb-0" id="picoEncaminhamentos">189</h6>
                <small class="text-muted">em Julho</small>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex align-items-center">
              <div class="me-2">
                <span class="badge bg-info">Menor</span>
              </div>
              <div>
                <h6 class="mb-0" id="menorEncaminhamentos">63</h6>
                <small class="text-muted">em Março</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('build/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('build/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
  @vite(['resources/js/pages/dashboard.js'])
  <script>
    // Gráfico de Pizza dos Encaminhamentos por Tipo
    document.addEventListener('DOMContentLoaded', function() {
      const examesPieChartElement = document.querySelector('#examesPieChart');
      if (examesPieChartElement) {
        const examesData = @json($examesPorTipo);
        
        const series = examesData.map(item => item.total === 0 ? 1 : item.total); // Converte 0 para 1 para visualização
        const labels = examesData.map(item => {
          return item.tipo_exame.charAt(0).toUpperCase() + item.tipo_exame.slice(1).replace('_', ' ');
        });
        
        // Cores específicas por tipo de exame
         const examTypeColors = {
           'admissional': '#28a745',    // Verde
           'periodico': '#007bff',      // Azul
           'demissional': '#dc3545',    // Vermelho
           'retorno': '#fd7e14',        // Laranja
           'mudanca_de_funcao': '#ffc107' // Amarelo
         };
        
        const colors = labels.map(label => {
          const normalizedLabel = label.toLowerCase().replace(/\s+/g, '_');
          return examTypeColors[normalizedLabel] || '#6c757d'; // Cor padrão se não encontrar
        });
        
        const examesPieChartOptions = {
           chart: {
             height: 350,
             type: 'pie',
             animations: {
               enabled: true,
               easing: 'easeinout',
               speed: 800,
               animateGradually: {
                 enabled: true,
                 delay: 150
               },
               dynamicAnimation: {
                 enabled: true,
                 speed: 350
               }
             },
             dropShadow: {
               enabled: true,
               top: 3,
               left: 3,
               blur: 6,
               opacity: 0.2
             },
             toolbar: {
               show: false
             },
             selection: {
               enabled: false
             },
             zoom: {
               enabled: false
             },
             events: {
               legendClick: function(chartContext, seriesIndex, config) {
                 return false;
               }
             }
           },
          series: series,
          labels: labels,
          colors: colors,
          fill: {
             type: 'solid'
           },
          stroke: {
             show: true,
             width: 3,
             colors: ['#ffffff']
           },
          legend: {
            show: false,
            position: 'bottom',
            floating: false,
            offsetX: 0,
            offsetY: 0,
            markers: {
              width: 0,
              height: 0,
              strokeWidth: 0,
              strokeColor: 'transparent',
              fillColors: 'transparent',
              radius: 0,
              customHTML: undefined,
              onClick: undefined,
              offsetX: 0,
              offsetY: 0
            }
          },
          dataLabels: {
             enabled: true,
             formatter: function (val, opts) {
               const value = opts.w.config.series[opts.seriesIndex];
               const percentage = val.toFixed(1);
               return value + '\n(' + percentage + '%)';
             },
             style: {
               fontSize: '13px',
               fontFamily: 'Inter, sans-serif',
               fontWeight: '700',
               colors: ['#ffffff']
             },
             dropShadow: {
               enabled: true,
               top: 1,
               left: 1,
               blur: 2,
               color: '#000',
               opacity: 0.5
             },
             background: {
               enabled: false
             }
           },
          plotOptions: {
             pie: {
               startAngle: -90,
               endAngle: 270,
               expandOnClick: false,
               offsetX: 0,
               offsetY: 0,
               customScale: 0.95,
               dataLabels: {
                 offset: 0,
                 minAngleToShowLabel: 15
               },
               donut: {
                 size: '0%',
                 background: 'transparent'
               }
             }
           },
          states: {
             hover: {
               filter: {
                 type: 'lighten',
                 value: 0.1
               }
             },
             active: {
               allowMultipleDataPointsSelection: false,
               filter: {
                 type: 'darken',
                 value: 0.1
               }
             }
           },
           tooltip: {
             enabled: true,
             theme: 'dark',
             style: {
               fontSize: '13px',
               fontFamily: 'Inter, sans-serif',
               fontWeight: '500'
             },
             y: {
               formatter: function(val, opts) {
                 const originalData = @json($examesPorTipo);
                 const realValue = originalData[opts.seriesIndex].total;
                 if (!opts || !opts.series || !Array.isArray(opts.series)) {
                   return realValue + ' encaminhamentos';
                 }
                 const total = opts.series.reduce((a, b) => a + b, 0);
                 const percentage = total > 0 ? ((realValue / total) * 100).toFixed(1) : 0;
                 return realValue + ' encaminhamentos (' + percentage + '%)';
               }
             },
             marker: {
               show: false
             },
             fixed: {
               enabled: false
             },
             followCursor: false,
             intersect: false,
             shared: false
           },
          responsive: [{
            breakpoint: 480,
            options: {
              chart: {
                width: 280,
                height: 300
              },
              plotOptions: {
                pie: {
                  customScale: 0.8
                }
              },
              dataLabels: {
                style: {
                  fontSize: '10px'
                }
              }
            }
          }]
        };
        
        const examesPieChart = new ApexCharts(examesPieChartElement, examesPieChartOptions);
        examesPieChart.render();
      }
    });
    
    // Disponibilizar dados de exames por mês para o JavaScript
    window.examesPorMes = @json($examesPorMes);
    
    // Disponibilizar dados de encaminhamentos por mês para o JavaScript
    window.encaminhamentosPorMes = @json($encaminhamentosPorMes);
    window.anosDisponiveis = @json($anosDisponiveis);
    
    // Gráfico de Encaminhamentos por Mês
    const encaminhamentosPorMesElement = document.querySelector('#encaminhamentosPorMesChart');
    if (encaminhamentosPorMesElement) {
      // Usar dados reais do controller
      const dadosEncaminhamentos = window.encaminhamentosPorMes;
      const anosDisponiveis = window.anosDisponiveis;
      
      // Definir ano inicial (primeiro ano disponível)
      const anoInicial = anosDisponiveis[0];
      let dadosAtuais = dadosEncaminhamentos[anoInicial] || {};
      
      const encaminhamentosChartOptions = {
        series: [{
          name: 'Encaminhamentos',
          data: Object.values(dadosAtuais)
        }],
        chart: {
          type: 'bar',
          height: 400,
          toolbar: {
            show: true,
            tools: {
              download: true,
              selection: false,
              zoom: false,
              zoomin: false,
              zoomout: false,
              pan: false,
              reset: false
            }
          },
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800
          }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '60%',
            endingShape: 'rounded',
            borderRadius: 4
          }
        },
        dataLabels: {
          enabled: true,
          style: {
            fontSize: '12px',
            fontFamily: 'Inter, sans-serif',
            fontWeight: '600',
            colors: ['#304758']
          },
          offsetY: -20
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: Object.keys(dadosAtuais),
          labels: {
            style: {
              fontSize: '12px',
              fontFamily: 'Inter, sans-serif',
              fontWeight: '500',
              colors: '#6c757d'
            }
          }
        },
        yaxis: {
          title: {
            text: 'Número de Encaminhamentos',
            style: {
              fontSize: '14px',
              fontFamily: 'Inter, sans-serif',
              fontWeight: '600',
              color: '#495057'
            }
          },
          labels: {
            style: {
              fontSize: '12px',
              fontFamily: 'Inter, sans-serif',
              fontWeight: '500',
              colors: '#6c757d'
            }
          }
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'light',
            type: 'vertical',
            shadeIntensity: 0.3,
            gradientToColors: ['#667eea'],
            inverseColors: false,
            opacityFrom: 0.8,
            opacityTo: 0.6,
            stops: [0, 100]
          }
        },
        colors: ['#764ba2'],
        tooltip: {
          theme: 'dark',
          style: {
            fontSize: '13px',
            fontFamily: 'Inter, sans-serif'
          },
          y: {
            formatter: function (val) {
              return val + ' encaminhamentos';
            }
          }
        },
        grid: {
          borderColor: '#e9ecef',
          strokeDashArray: 3,
          xaxis: {
            lines: {
              show: false
            }
          },
          yaxis: {
            lines: {
              show: true
            }
          }
        },
        responsive: [{
          breakpoint: 768,
          options: {
            chart: {
              height: 300
            },
            plotOptions: {
              bar: {
                columnWidth: '80%'
              }
            }
          }
        }]
      };
      
      const encaminhamentosChart = new ApexCharts(encaminhamentosPorMesElement, encaminhamentosChartOptions);
      encaminhamentosChart.render();
      
      // Função para atualizar estatísticas
      function atualizarEstatisticas(dados) {
        const valores = Object.values(dados);
        const total = valores.reduce((a, b) => a + b, 0);
        const media = Math.round(total / valores.length);
        const maximo = Math.max(...valores);
        const minimo = Math.min(...valores);
        const mesPico = Object.keys(dados)[valores.indexOf(maximo)];
        const mesMenor = Object.keys(dados)[valores.indexOf(minimo)];
        
        document.getElementById('totalEncaminhamentosAno').textContent = total.toLocaleString('pt-BR');
        document.getElementById('mediaEncaminhamentosMes').textContent = media;
        document.getElementById('picoEncaminhamentos').textContent = maximo;
        document.getElementById('menorEncaminhamentos').textContent = minimo;
        document.querySelector('#picoEncaminhamentos').nextElementSibling.textContent = `em ${mesPico}`;
        document.querySelector('#menorEncaminhamentos').nextElementSibling.textContent = `em ${mesMenor}`;
      }
      
      // Atualizar estatísticas iniciais
      atualizarEstatisticas(dadosAtuais);
      
      // Event listener para mudança de ano
      document.getElementById('anoSelecionado').addEventListener('change', function() {
        const anoSelecionado = this.value;
        
        // Usar dados reais do controller
        dadosAtuais = dadosEncaminhamentos[anoSelecionado] || {};
        
        // Atualizar gráfico
        encaminhamentosChart.updateSeries([{
          name: 'Encaminhamentos',
          data: Object.values(dadosAtuais)
        }]);
        
        // Atualizar categorias do eixo X
        encaminhamentosChart.updateOptions({
          xaxis: {
            categories: Object.keys(dadosAtuais)
          }
        });
        
        // Atualizar estatísticas
        atualizarEstatisticas(dadosAtuais);
      });
    }
  </script>
@endpush
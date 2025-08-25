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
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total de Empresas cadastradas</h6>
              <div class="dropdown mb-2">
                <a type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">{{ number_format($totalEmpresas, 0, ',', '.') }}</h3>
                <!--<h3 class="mb-2">3,897</h3>-->
              </div>
              <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-center align-items-center">
                <i data-lucide="home" style="width: 40px; height: 40px;"></i>
                <!--<div id="customersChart" class="mt-md-3 mt-xl-0"></div>-->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total de Funcionários cadastrados</h6>
              <div class="dropdown mb-2">
                <a type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">{{ number_format($totalFuncionarios, 0, ',', '.') }}</h3>
                <div class="d-flex align-items-baseline">
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="ordersChart" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total de Exames Pendentes</h6>
              <div class="dropdown mb-2">
                <a type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">35,084</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-danger">
                    <span>-2.8%</span>
                    <i data-lucide="arrow-down" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="ordersChart" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total de Exames Gerados</h6>
              <div class="dropdown mb-2">
                <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">89.87%</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-success">
                    <span>+2.8%</span>
                    <i data-lucide="arrow-up" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
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
        <div id="storageChart"></div>
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
@endsection

@push('plugin-scripts')
  <script src="{{ asset('build/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('build/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
  @vite(['resources/js/pages/dashboard.js'])
@endpush
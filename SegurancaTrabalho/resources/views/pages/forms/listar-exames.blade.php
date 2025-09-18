@extends('layout.master')

@push('plugin-styles')
  {{-- Se usar select2/flatpickr, adicione aqui os CSS do seu template, se já não estiverem globais --}}
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
  
  /* Estilos customizados para paginação */
  .pagination-rounded .page-link {
    border-radius: 8px !important;
    margin: 0 2px;
    border: 1px solid #e9ecef;
    color: #6c757d;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease-in-out;
  }
  
  .pagination-rounded .page-link:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .pagination-rounded .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
  }
  
  .pagination-rounded .page-item.disabled .page-link {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    color: #adb5bd;
  }
  
  /* Melhorias na tabela */
  .table-responsive {
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  }
  
  .table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem 0.75rem;
  }
  
  .table tbody tr {
    transition: all 0.2s ease;
  }
  
  .table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
</style>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Encaminhamentos para Exames</h4>
    <p class="text-muted m-0">Listagem dos encaminhamentos cadastrados no sistema com filtros disponíveis.</p>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card shadow-sm rounded-3">
      <div class="card-body">
        {{-- Filtros --}}
        <form id="formFiltros" class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Empresa</label>
            <select id="filtroEmpresa" class="form-select">
              <option value="">Todas</option>
              @foreach($empresas as $e)
                <option value="{{ $e->id }}">{{ $e->nome }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Funcionário</label>
            <select id="filtroFuncionario" class="form-select">
              <option value="">Todos</option>
              @foreach($funcionarios as $f)
                <option value="{{ $f->id }}" data-empresa="{{ $f->empresa_id }}">{{ $f->nome }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Tipo</label>
            <select id="filtroTipo" class="form-select">
              <option value="">Todos</option>
              <option value="admissional">Admissional</option>
              <option value="demissional">Demissional</option>
              <option value="periodico">Periódico</option>
              <option value="retorno">Retorno</option>
              <option value="mudanca_funcao">Mudança de Função</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Status</label>
            <select id="filtroStatus" class="form-select">
              <option value="">Todos</option>
              <option value="pendente">Pendente</option>
              <option value="agendado">Agendado</option>
              <option value="realizado">Realizado</option>
              <option value="vencido">Vencido</option>
              <option value="cancelado">Cancelado</option>
            </select>
          </div>

          <div class="col-md-2 d-flex gap-2">
            <button type="button" id="btnAplicar" class="btn btn-primary w-100">Aplicar</button>
            <button type="button" id="btnLimpar" class="btn btn-outline-secondary w-100">Limpar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Tabela --}}
  <div class="col-12">
    <div class="card shadow-sm rounded-3">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle" id="tabelaExames">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Empresa</th>
                <th>Funcionário</th>
                <th>Tipo</th>
                <th>Título</th>
                <th>Status</th>
                <th>Solicitado</th>
                <th>Agendado</th>
                <th>Realizado</th>
                <th>Validade</th>
                <th class="text-end">Ações</th>
              </tr>
            </thead>
            <tbody>
              @foreach($exames as $x)
                <tr
                  data-empresa="{{ $x->empresa_id }}"
                  data-funcionario="{{ $x->funcionario_id }}"
                  data-tipo="{{ $x->tipo_exame }}"
                  data-status="{{ $x->status ?? 'pendente' }}"
                >
                  <td>{{ $x->id }}</td>
                  <td>{{ $x->empresa->razao_social ?? 'N/A' }}</td>
                  <td>{{ $x->funcionario->nome ?? 'N/A' }}</td>
                  <td class="text-capitalize">{{ str_replace('_',' ', $x->tipo_exame) }}</td>
                  <td>ASO {{ ucfirst(str_replace('_',' ', $x->tipo_exame)) }}</td>
                  <td>
                    @php
                      $status = $x->status ?? 'pendente';
                      $map = [
                        'pendente' => 'badge bg-warning text-dark',
                        'agendado' => 'badge bg-info',
                        'realizado' => 'badge bg-success',
                        'vencido' => 'badge bg-danger',
                        'cancelado' => 'badge bg-secondary',
                      ];
                    @endphp
                    <span class="{{ $map[$status] ?? 'badge bg-light text-dark' }}">{{ ucfirst($status) }}</span>
                  </td>
                  <td>{{ $x->created_at->format('d/m/Y') ?? '-' }}</td>
                  <td>{{ $x->data_agendamento ? \Carbon\Carbon::parse($x->data_agendamento)->format('d/m/Y') : '-' }}</td>
                  <td>{{ $x->data_atendimento ? \Carbon\Carbon::parse($x->data_atendimento)->format('d/m/Y') : '-' }}</td>
                  <td>{{ $x->data_atendimento ? \Carbon\Carbon::parse($x->data_atendimento)->addYear()->format('d/m/Y') : '-' }}</td>
                  <td class="text-end">
                    <a
                      href="{{ route('forms.exames.imprimir', $x->id) }}"
                      class="btn btn-sm btn-outline-success"
                      target="_blank"
                      title="Imprimir Encaminhamento"
                    >
                      <i data-lucide="printer" class="w-4 h-4 me-1"></i>Imprimir
                    </a>
                  </td>
                </tr>
              @endforeach

              @if($exames->isEmpty())
                <tr>
                  <td colspan="11" class="text-center text-muted py-4">
                    Nenhum encaminhamento encontrado.
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        @if($exames->hasPages())
          <div class="mt-4 px-3">
            {{ $exames->links('custom.pagination') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- Modal Detalhes --}}
<div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetalhesLabel">Detalhes do Exame</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <dl class="row mb-0" id="dlDetalhes">
          {{-- preenchido via JS --}}
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  {{-- Se precisar, inclua JS de plugins aqui --}}
@endpush

@push('custom-scripts')
<script>
(function() {
  const filtroEmpresa = document.getElementById('filtroEmpresa');
  const filtroFuncionario = document.getElementById('filtroFuncionario');
  const filtroTipo = document.getElementById('filtroTipo');
  const filtroStatus = document.getElementById('filtroStatus');
  const btnAplicar = document.getElementById('btnAplicar');
  const btnLimpar = document.getElementById('btnLimpar');
  const linhas = Array.from(document.querySelectorAll('#tabelaExames tbody tr'));

  // Filtra funcionários por empresa selecionada (opcional)
  filtroEmpresa?.addEventListener('change', function() {
    const empresaId = this.value;
    for (const opt of filtroFuncionario.options) {
      if (!opt.value) { opt.hidden = false; continue; }
      const empDoFunc = opt.getAttribute('data-empresa');
      opt.hidden = (empresaId && empDoFunc !== empresaId);
    }
    // Se o funcionário atual não pertence à empresa escolhida, limpa seleção
    if (filtroFuncionario.selectedOptions[0]?.hidden) filtroFuncionario.value = '';
  });

  function aplicaFiltros() {
    const emp = filtroEmpresa.value;
    const fun = filtroFuncionario.value;
    const tip = filtroTipo.value;
    const sta = filtroStatus.value;

    linhas.forEach(tr => {
      const okEmp = !emp || tr.dataset.empresa === emp;
      const okFun = !fun || tr.dataset.funcionario === fun;
      const okTip = !tip || tr.dataset.tipo === tip;
      const okSta = !sta || tr.dataset.status === sta;
      tr.style.display = (okEmp && okFun && okTip && okSta) ? '' : 'none';
    });
  }

  btnAplicar?.addEventListener('click', aplicaFiltros);

  btnLimpar?.addEventListener('click', function() {
    filtroEmpresa.value = '';
    filtroFuncionario.value = '';
    filtroTipo.value = '';
    filtroStatus.value = '';
    linhas.forEach(tr => tr.style.display = '');
  });

  // Modal Detalhes
  const modal = document.getElementById('modalDetalhes');
  if (modal) {
    modal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const reg = button ? button.getAttribute('data-reg') : null;
      const dado = reg ? JSON.parse(reg) : null;

      const dl = document.getElementById('dlDetalhes');
      dl.innerHTML = '';

      if (dado) {
        const fields = [
          ['ID', dado.id],
          ['Empresa', dado.empresa],
          ['Funcionário', dado.funcionario],
          ['Tipo', (dado.tipo || '').replace('_',' ')],
          ['Título', dado.titulo],
          ['Status', dado.status],
          ['Data Solicitação', dado.data_solicitacao || '-'],
          ['Data Agendamento', dado.data_agendamento || '-'],
          ['Data Realização', dado.data_realizacao || '-'],
          ['Validade Até', dado.validade_ate || '-'],
          ['Observações', dado.observacoes || '-'],
        ];
        fields.forEach(([label, value]) => {
          dl.insertAdjacentHTML('beforeend', `
            <dt class="col-sm-4 text-muted">${label}</dt>
            <dd class="col-sm-8">${value ?? '-'}</dd>
          `);
        });
      }
    });
  }
})();
</script>
@endpush

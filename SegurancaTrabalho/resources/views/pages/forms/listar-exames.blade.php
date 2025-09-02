@extends('layout.master')

@push('plugin-styles')
  {{-- Se usar select2/flatpickr, adicione aqui os CSS do seu template, se já não estiverem globais --}}
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Exames</h4>
    <p class="text-muted m-0">Visualização e filtros (dados de exames estão mockados apenas para layout).</p>
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
                  data-empresa="{{ $x['empresa_id'] }}"
                  data-funcionario="{{ $x['funcionario_id'] }}"
                  data-tipo="{{ $x['tipo'] }}"
                  data-status="{{ $x['status'] }}"
                >
                  <td>{{ $x['id'] }}</td>
                  <td>{{ $x['empresa'] }}</td>
                  <td>{{ $x['funcionario'] }}</td>
                  <td class="text-capitalize">{{ str_replace('_',' ',$x['tipo']) }}</td>
                  <td>{{ $x['titulo'] }}</td>
                  <td>
                    @php
                      $map = [
                        'pendente' => 'badge bg-warning text-dark',
                        'agendado' => 'badge bg-info',
                        'realizado' => 'badge bg-success',
                        'vencido' => 'badge bg-danger',
                        'cancelado' => 'badge bg-secondary',
                      ];
                    @endphp
                    <span class="{{ $map[$x['status']] ?? 'badge bg-light text-dark' }}">{{ ucfirst($x['status']) }}</span>
                  </td>
                  <td>{{ $x['data_solicitacao'] ?? '-' }}</td>
                  <td>{{ $x['data_agendamento'] ?? '-' }}</td>
                  <td>{{ $x['data_realizacao'] ?? '-' }}</td>
                  <td>{{ $x['validade_ate'] ?? '-' }}</td>
                  <td class="text-end">
                    <a
                      href="{{ route('forms.exames.imprimir', $x['id']) }}"
                      class="btn btn-sm btn-outline-success"
                      target="_blank"
                    >
                      <i class="fas fa-print me-1"></i>Imprimir
                    </a>
                  </td>
                </tr>
              @endforeach

              @if($exames->isEmpty())
                <tr>
                  <td colspan="11" class="text-center text-muted py-4">
                    Nenhum exame para exibir (mock).
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        <small class="text-muted">
          *Esta listagem usa dados mockados para visual. Ao integrar com o banco, substitua pelo collection real.
        </small>
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

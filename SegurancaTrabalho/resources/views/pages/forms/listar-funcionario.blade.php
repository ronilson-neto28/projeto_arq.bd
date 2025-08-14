@extends('layout.master')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Funcionários</h4>
    <p class="text-muted m-0">Listagem com filtros por Empresa e busca geral.</p>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card shadow-sm rounded-3">
      <div class="card-body">
        <form id="formFiltroFuncs" class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Empresa</label>
            <select id="filtroEmpresaFunc" class="form-select">
              <option value="">Todas</option>
              {{-- opções serão preenchidas via JS a partir das linhas --}}
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Cargo</label>
            <select id="filtroCargoFunc" class="form-select">
              <option value="">Todos</option>
              {{-- opções via JS (se houver cargo na linha) --}}
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Busca</label>
            <input type="text" id="buscaFunc" class="form-control" placeholder="Nome, e-mail...">
          </div>
          <div class="col-md-2 d-flex gap-2">
            <button type="button" id="btnAplicarFunc" class="btn btn-primary w-100">Aplicar</button>
            <button type="button" id="btnLimparFunc" class="btn btn-outline-secondary w-100">Limpar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card shadow-sm rounded-3">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle" id="tabelaFuncionarios">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Funcionário</th>
                <th>Empresa</th>
                <th>Cargo</th>
                <th>E-mail</th>
                <th>Gênero</th>
                <th>Nascimento</th>
                <th class="text-end">Ações</th>
              </tr>
            </thead>
            <tbody>
              @forelse(($funcionarios ?? []) as $f)
                @php
                  $empresaNome = $f->empresa->nome ?? ($f->empresa_nome ?? '');
                  $cargoNome   = $f->cargo->nome ?? ($f->cargo_nome ?? '');
                  $nascFmt     = !empty($f->data_nascimento) ? \Carbon\Carbon::parse($f->data_nascimento)->format('d/m/Y') : '-';
                  $createdFmt  = optional($f->created_at)->format('d/m/Y') ?? '-';
                  $updatedFmt  = optional($f->updated_at)->format('d/m/Y') ?? '-';
                @endphp
                <tr
                  data-empresa-id="{{ $f->empresa_id ?? '' }}"
                  data-empresa-nome="{{ $empresaNome }}"
                  data-cargo-nome="{{ $cargoNome }}"
                  data-nome="{{ $f->nome ?? '' }}"
                  data-email="{{ $f->email ?? '' }}"
                >
                  <td>{{ $f->id }}</td>
                  <td>{{ $f->nome ?? '-' }}</td>
                  <td>{{ $empresaNome ?: '-' }}</td>
                  <td>{{ $cargoNome ?: '-' }}</td>
                  <td>{{ $f->email ?? '-' }}</td>
                  <td class="text-capitalize">{{ $f->genero ?? '-' }}</td>
                  <td>{{ $nascFmt }}</td>
                  <td class="text-end">
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#modalFuncionario"
                      data-id="{{ $f->id }}"
                      data-nome="{{ $f->nome ?? '-' }}"
                      data-email="{{ $f->email ?? '-' }}"
                      data-empresa="{{ $empresaNome ?: '-' }}"
                      data-cargo="{{ $cargoNome ?: '-' }}"
                      data-genero="{{ $f->genero ?? '-' }}"
                      data-nascimento="{{ $nascFmt }}"
                      data-created="{{ $createdFmt }}"
                      data-updated="{{ $updatedFmt }}"
                    >
                      Detalhes
                    </button>
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Nenhum funcionário encontrado.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <small class="text-muted">Os filtros de Empresa e Cargo são preenchidos automaticamente a partir da listagem.</small>
      </div>
    </div>
  </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modalFuncionario" tabindex="-1" aria-labelledby="modalFuncionarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFuncionarioLabel">Detalhes do Funcionário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <dl class="row mb-0" id="dlFuncionario"></dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom-scripts')
<script>
(function(){
  const q = (s)=>document.querySelector(s);
  const qa = (s)=>Array.from(document.querySelectorAll(s));
  const selEmp = q('#filtroEmpresaFunc');
  const selCargo = q('#filtroCargoFunc');
  const busca = q('#buscaFunc');
  const btnA = q('#btnAplicarFunc');
  const btnL = q('#btnLimparFunc');
  const linhas = qa('#tabelaFuncionarios tbody tr');

  function normaliza(t){ return (t||'').toString().toLowerCase().trim(); }

  // Preenche selects a partir das linhas
  const empresasSet = new Set(), cargosSet = new Set();
  linhas.forEach(tr=>{
    const en = tr.getAttribute('data-empresa-nome') || '';
    const cn = tr.getAttribute('data-cargo-nome') || '';
    if (en) empresasSet.add(en);
    if (cn) cargosSet.add(cn);
  });
  [...empresasSet].sort().forEach(n=>{
    const opt = document.createElement('option'); opt.value = n; opt.textContent = n; selEmp?.appendChild(opt);
  });
  [...cargosSet].sort().forEach(n=>{
    const opt = document.createElement('option'); opt.value = n; opt.textContent = n; selCargo?.appendChild(opt);
  });

  function aplica(){
    const emp = normaliza(selEmp.value);
    const cargo = normaliza(selCargo.value);
    const txt = normaliza(busca.value);

    linhas.forEach(tr=>{
      const trEmp = normaliza(tr.getAttribute('data-empresa-nome'));
      const trCar = normaliza(tr.getAttribute('data-cargo-nome'));
      const cols = [
        tr.getAttribute('data-nome'),
        tr.getAttribute('data-email'),
        trEmp, trCar
      ].map(normaliza).join(' ');
      const okEmp = !emp || trEmp === emp;
      const okCargo = !cargo || trCar === cargo;
      const okTxt = !txt || cols.includes(txt);
      tr.style.display = (okEmp && okCargo && okTxt) ? '' : 'none';
    });
  }

  btnA?.addEventListener('click', aplica);
  [selEmp, selCargo].forEach(el => el?.addEventListener('change', aplica));
  busca?.addEventListener('keydown', e=>{ if(e.key==='Enter'){ e.preventDefault(); aplica(); } });

  btnL?.addEventListener('click', ()=>{
    selEmp.value=''; selCargo.value=''; busca.value='';
    linhas.forEach(tr=> tr.style.display='');
  });

  // ===== Modal seguro (sem JSON.parse; usa data-* + textContent) =====
  function addRow(dl, label, value) {
    const dt = document.createElement('dt');
    dt.className = 'col-sm-4 text-muted';
    dt.textContent = label;

    const dd = document.createElement('dd');
    dd.className = 'col-sm-8';
    dd.textContent = value ?? '-';

    dl.appendChild(dt);
    dl.appendChild(dd);
  }

  const modal = document.getElementById('modalFuncionario');
  modal?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;

    const d = {
      id: btn.getAttribute('data-id'),
      nome: btn.getAttribute('data-nome'),
      email: btn.getAttribute('data-email'),
      empresa: btn.getAttribute('data-empresa'),
      cargo: btn.getAttribute('data-cargo'),
      genero: btn.getAttribute('data-genero'),
      data_nascimento: btn.getAttribute('data-nascimento'),
      created_at: btn.getAttribute('data-created'),
      updated_at: btn.getAttribute('data-updated'),
    };

    const dl = document.getElementById('dlFuncionario');
    dl.innerHTML = '';

    addRow(dl, 'ID', d.id);
    addRow(dl, 'Nome', d.nome);
    addRow(dl, 'E-mail', d.email);
    addRow(dl, 'Empresa', d.empresa);
    addRow(dl, 'Cargo', d.cargo);
    addRow(dl, 'Gênero', d.genero);
    addRow(dl, 'Nascimento', d.data_nascimento);
    addRow(dl, 'Criado em', d.created_at);
    addRow(dl, 'Atualizado em', d.updated_at);
  });
})();
</script>
@endpush

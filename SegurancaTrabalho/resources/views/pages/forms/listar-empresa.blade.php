@extends('layout.master')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Empresas</h4>
    <p class="text-muted m-0">Listagem e filtros.</p>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card shadow-sm rounded-3">
      <div class="card-body">
        <form id="formFiltroEmpresas" class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Busca</label>
            <input type="text" id="buscaEmpresa" class="form-control" placeholder="Nome, CNPJ, e-mail, cidade...">
          </div>
          <div class="col-md-3">
            <label class="form-label">UF</label>
            <input type="text" id="filtroUf" class="form-control" placeholder="Ex: PA" maxlength="2">
          </div>
          <div class="col-md-3">
            <label class="form-label">Cidade</label>
            <input type="text" id="filtroCidade" class="form-control" placeholder="Ex: Santarém">
          </div>
          <div class="col-md-2 d-flex gap-2">
            <button type="button" id="btnAplicarEmp" class="btn btn-primary w-100">Aplicar</button>
            <button type="button" id="btnLimparEmp" class="btn btn-outline-secondary w-100">Limpar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card shadow-sm rounded-3">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle" id="tabelaEmpresas">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Empresa</th>
                <th>CNPJ</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Cidade/UF</th>
                <th>Criada em</th>
                <th class="text-end">Ações</th>
              </tr>
            </thead>
            <tbody>
              @forelse($empresas ?? [] as $e)
                <tr
                  data-nome="{{ $e->nome ?? '' }}"
                  data-cnpj="{{ $e->cnpj ?? '' }}"
                  data-email="{{ $e->email ?? '' }}"
                  data-tel="{{ $e->telefone ?? '' }}"
                  data-cidade="{{ $e->cidade ?? '' }}"
                  data-uf="{{ $e->uf ?? '' }}"
                >
                  <td>{{ $e->id }}</td>
                  <td>{{ $e->nome ?? '-' }}</td>
                  <td>{{ $e->cnpj ?? '-' }}</td>
                  <td>{{ $e->email ?? '-' }}</td>
                  <td>{{ $e->telefone ?? '-' }}</td>
                  <td>
                    @php $cid = $e->cidade ?? null; $uf = $e->uf ?? null; @endphp
                    {{ $cid ? $cid : '-' }}{{ $cid && $uf ? ' / ' : '' }}{{ $uf ?? '' }}
                  </td>
                  <td>{{ optional($e->created_at)->format('d/m/Y') ?? '-' }}</td>
                  <td class="text-end">
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#modalEmpresa"
                      data-id="{{ $e->id }}"
                      data-nome="{{ $e->nome ?? '-' }}"
                      data-cnpj="{{ $e->cnpj ?? '-' }}"
                      data-email="{{ $e->email ?? '-' }}"
                      data-telefone="{{ $e->telefone ?? '-' }}"
                      data-cidade="{{ $e->cidade ?? '-' }}"
                      data-uf="{{ $e->uf ?? '-' }}"
                      data-created="{{ optional($e->created_at)->format('d/m/Y') ?? '-' }}"
                      data-updated="{{ optional($e->updated_at)->format('d/m/Y') ?? '-' }}"
                    >
                      Detalhes
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">
                    Nenhuma empresa encontrada.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <small class="text-muted">Use a busca para filtrar por qualquer coluna.</small>
      </div>
    </div>
  </div>
</div>

{{-- Modal de Detalhes --}}
<div class="modal fade" id="modalEmpresa" tabindex="-1" aria-labelledby="modalEmpresaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEmpresaLabel">Detalhes da Empresa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <dl class="row mb-0" id="dlEmpresa"></dl>
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

  const busca = q('#buscaEmpresa');
  const uf = q('#filtroUf');
  const cidade = q('#filtroCidade');
  const btnAplicar = q('#btnAplicarEmp');
  const btnLimpar = q('#btnLimparEmp');
  const linhas = qa('#tabelaEmpresas tbody tr');

  function normaliza(t){ return (t||'').toString().toLowerCase().trim(); }

  function aplicaFiltro(){
    const txt = normaliza(busca.value);
    const ufv = normaliza(uf.value);
    const cidv = normaliza(cidade.value);

    linhas.forEach(tr=>{
      const cols = [
        tr.dataset.nome, tr.dataset.cnpj, tr.dataset.email,
        tr.dataset.tel, tr.dataset.cidade, tr.dataset.uf
      ].map(normaliza).join(' ');
      const okTxt = !txt || cols.includes(txt);
      const okUf = !ufv || normaliza(tr.dataset.uf) === ufv;
      const okCid = !cidv || normaliza(tr.dataset.cidade).includes(cidv);
      tr.style.display = (okTxt && okUf && okCid) ? '' : 'none';
    });
  }

  btnAplicar?.addEventListener('click', aplicaFiltro);
  [busca, uf, cidade].forEach(el => el?.addEventListener('keydown', e => {
    if(e.key==='Enter'){ e.preventDefault(); aplicaFiltro(); }
  }));

  btnLimpar?.addEventListener('click', ()=>{
    busca.value=''; uf.value=''; cidade.value='';
    linhas.forEach(tr=> tr.style.display = '');
  });

  // ===== Modal seguro: popula com data-* e textContent (sem JSON, sem innerHTML de valores) =====
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

  const modal = document.getElementById('modalEmpresa');
  modal?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const d = {
      id: btn.getAttribute('data-id'),
      nome: btn.getAttribute('data-nome'),
      cnpj: btn.getAttribute('data-cnpj'),
      email: btn.getAttribute('data-email'),
      telefone: btn.getAttribute('data-telefone'),
      cidade: btn.getAttribute('data-cidade'),
      uf: btn.getAttribute('data-uf'),
      created_at: btn.getAttribute('data-created'),
      updated_at: btn.getAttribute('data-updated'),
    };

    const dl = document.getElementById('dlEmpresa');
    dl.innerHTML = ''; // limpa o conteúdo anterior do modal

    addRow(dl, 'ID', d.id);
    addRow(dl, 'Empresa', d.nome);
    addRow(dl, 'CNPJ', d.cnpj);
    addRow(dl, 'E-mail', d.email);
    addRow(dl, 'Telefone', d.telefone);
    addRow(dl, 'Cidade', d.cidade);
    addRow(dl, 'UF', d.uf);
    addRow(dl, 'Criada em', d.created_at);
    addRow(dl, 'Atualizada em', d.updated_at);
  });
})();
</script>
@endpush

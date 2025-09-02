<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    // GET /empresas/listar
    public function index(Request $request)
    {
        $busca = trim((string) $request->get('search', ''));

        $empresas = Empresa::with('cnae')
            ->when($busca !== '', function ($q) use ($busca) {
                // Para Postgres, use ILIKE; para MySQL, LIKE já é case-insensitive por padrão
                $driver = DB::getDriverName();
                $likeOp = $driver === 'pgsql' ? 'ilike' : 'like';

                $q->where(function ($w) use ($busca, $likeOp) {
                    $w->where('razao_social', $likeOp, "%{$busca}%")
                      ->orWhere('nome_fantasia', $likeOp, "%{$busca}%")
                      ->orWhere('cnpj', 'like', "%{$busca}%");
                });
            })
            ->orderBy('razao_social')
            ->paginate(15)
            ->appends(['search' => $busca]);

        // ajuste o caminho da view conforme sua estrutura
        return view('pages.forms.listar-empresa', compact('empresas', 'busca'));
    }

    // GET /empresas/cadastrar
    public function create()
    {
        // Se sua tela precisar listar CNAEs no select:
        $cnaes = DB::table('cnaes')->select('id','codigo','descricao','grau_risco')->orderBy('codigo')->get();

        // ajuste o caminho da view conforme sua estrutura
        return view('pages.forms.cadastrar-empresa', compact('cnaes'));
    }

    // POST /empresas/cadastrar
    public function store(Request $request)
    {
        $data = $request->validate([
            'razao_social'  => ['required', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj'          => ['required', 'regex:/^\D*\d{14}\D*$/', 'unique:empresas,cnpj'],
            'cnae_id'       => ['nullable', 'exists:cnaes,id'],
            'grau_risco'    => ['nullable', 'integer', 'between:1,4'],
            'cep'           => ['nullable', 'regex:/^\D*\d{8}\D*$/'],
            'endereco'      => ['nullable', 'string', 'max:255'],
            'bairro'        => ['nullable', 'string', 'max:255'],
            'cidade'        => ['nullable', 'string', 'max:255'],
            'uf'            => ['nullable', 'string', 'size:2'],
            'email'         => ['nullable', 'email', 'max:255'],
        ]);

        // normaliza CNPJ/CEP antes de salvar
        $data['cnpj'] = preg_replace('/\D+/', '', $data['cnpj']);
        if (!empty($data['cep'])) {
            $data['cep'] = preg_replace('/\D+/', '', $data['cep']);
        }

        // snapshot automático do grau de risco a partir do CNAE (se não informado)
        if (empty($data['grau_risco']) && !empty($data['cnae_id'])) {
            $data['grau_risco'] = DB::table('cnaes')->where('id', $data['cnae_id'])->value('grau_risco');
        }

        Empresa::create($data);

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa cadastrada com sucesso!');
    }

    // (Opcional) editar/atualizar/excluir se quiser completar o CRUD depois:
    // public function edit(Empresa $empresa) { ... }
    // public function update(Request $request, Empresa $empresa) { ... }
    // public function destroy(Empresa $empresa) { ... }
}

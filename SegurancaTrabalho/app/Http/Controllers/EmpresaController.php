<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Cnae;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    // GET /empresas/listar
    public function index(Request $request)
    {
        $busca = trim((string) $request->get('search', ''));

        $empresas = Empresa::with(['cnae', 'telefones'])
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
        // Normalizar dados antes da validação
        $cnpj = preg_replace('/\D+/', '', $request->input('cnpj', ''));
        $cep = preg_replace('/\D+/', '', $request->input('cep', ''));
        
        $data = $request->validate([
            'razao_social'  => ['required', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj'          => ['required', 'string'],
            'cnae_id'       => ['nullable', 'exists:cnaes,id'],
            'grau_risco'    => ['nullable', 'integer', 'between:1,4'],
            'cep'           => ['nullable', 'string'],
            'endereco'      => ['nullable', 'string', 'max:255'],
            'bairro'        => ['nullable', 'string', 'max:255'],
            'cidade'        => ['nullable', 'string', 'max:255'],
            'uf'            => ['nullable', 'string', 'size:2'],
            'email'         => ['nullable', 'email', 'max:255'],
            'telefone'      => ['nullable', 'string', 'max:20'],
        ]);
        
        // Validações customizadas após normalização
        if (strlen($cnpj) !== 14) {
            return back()->withErrors(['cnpj' => 'O CNPJ deve conter exatamente 14 dígitos.'])->withInput();
        }
        
        if (!empty($cep) && strlen($cep) !== 8) {
            return back()->withErrors(['cep' => 'O CEP deve conter exatamente 8 dígitos.'])->withInput();
        }
        
        // Verificar se CNPJ já existe
        if (\App\Models\Empresa::where('cnpj', $cnpj)->exists()) {
            return back()->withErrors(['cnpj' => 'Este CNPJ já está cadastrado.'])->withInput();
        }

        // Usar valores normalizados
        $data['cnpj'] = $cnpj;
        if (!empty($cep)) {
            $data['cep'] = $cep;
        }

        // snapshot automático do grau de risco a partir do CNAE (se não informado)
        if (empty($data['grau_risco']) && !empty($data['cnae_id'])) {
            $data['grau_risco'] = DB::table('cnaes')->where('id', $data['cnae_id'])->value('grau_risco');
        }

        // Separar telefone dos dados da empresa
        $telefone = $data['telefone'] ?? null;
        unset($data['telefone']);

        // Criar a empresa
        $empresa = Empresa::create($data);

        // Salvar telefone na tabela telefones se fornecido
        if (!empty($telefone)) {
            DB::table('telefones')->insert([
                'empresa_id' => $empresa->id,
                'numero' => $telefone,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa cadastrada com sucesso!');
    }

    /**
     * Exibe o formulário de edição da empresa
     */
    public function edit($id)
    {
        $empresa = Empresa::with('telefones')->findOrFail($id);
        $cnaes = Cnae::orderBy('codigo')->get();
        
        return view('pages.forms.editar-empresa', compact('empresa', 'cnaes'));
    }

    /**
     * Atualiza os dados da empresa
     */
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        
        // Validação
        $request->validate([
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnpj' => 'required|string|max:18|unique:empresas,cnpj,' . $id,
            'cnae_id' => 'nullable|exists:cnaes,id',
            'grau_risco' => 'nullable|in:1,2,3,4',
            'cep' => 'nullable|string|max:9',
            'endereco' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20'
        ]);

        // Normalizar CNPJ e CEP
        $cnpj = preg_replace('/[^0-9]/', '', $request->cnpj);
        $cep = preg_replace('/[^0-9]/', '', $request->cep ?? '');
        $telefone = $request->telefone;

        // Separar dados da empresa dos dados do telefone
        $empresaData = $request->except(['telefone', '_token', '_method']);
        $empresaData['cnpj'] = $cnpj;
        $empresaData['cep'] = $cep ?: null;

        // Atualizar empresa
        $empresa->update($empresaData);

        // Atualizar telefone
        if (!empty($telefone)) {
            // Verificar se já existe telefone para esta empresa
            $telefoneExistente = DB::table('telefones')->where('empresa_id', $empresa->id)->first();
            
            if ($telefoneExistente) {
                // Atualizar telefone existente
                DB::table('telefones')
                    ->where('empresa_id', $empresa->id)
                    ->update([
                        'numero' => $telefone,
                        'updated_at' => now()
                    ]);
            } else {
                // Criar novo telefone
                DB::table('telefones')->insert([
                    'empresa_id' => $empresa->id,
                    'numero' => $telefone,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            // Remover telefone se campo estiver vazio
            DB::table('telefones')->where('empresa_id', $empresa->id)->delete();
        }

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    /**
     * Remove a empresa do banco de dados
     */
    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        
        // Verificar se a empresa tem funcionários vinculados
        $funcionariosCount = DB::table('funcionarios')->where('empresa_id', $id)->count();
        
        if ($funcionariosCount > 0) {
            return redirect()
                ->route('empresas.index')
                ->with('error', 'Não é possível excluir a empresa pois ela possui funcionários vinculados.');
        }
        
        // Remover telefones da empresa
        DB::table('telefones')->where('empresa_id', $id)->delete();
        
        // Remover empresa
        $empresa->delete();
        
        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa excluída com sucesso!');
    }
}

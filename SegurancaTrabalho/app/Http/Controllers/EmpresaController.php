<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Cnae;

class EmpresaController extends Controller
{
    public function create()
    {
        $cnaes = Cnae::query()->orderBy('codigo')->get();
        return view('pages.forms.cadastrar-empresa', compact('cnaes'));
    }

    public function index(Request $request)
    {
        $query = Empresa::with(['cnae','telefones']);

        $busca = $request->input('buscaEmpresa');
        $cnaeId = $request->input('filtroCnae');
        $uf = $request->input('filtroUf');
        $cidade = $request->input('filtroCidade');

        if ($cnaeId) {
            $query->where('cnae_id', $cnaeId);
        }

        if ($uf) {
            $query->where('uf', $uf);
        }

        if ($cidade) {
            $query->where('cidade', 'like', "%{$cidade}%");
        }

        if ($busca) {
            $query->where(function ($q) use ($busca) {
                $q->where('razao_social', 'like', "%{$busca}%")
                  ->orWhere('cnpj', 'like', "%{$busca}%")
                  ->orWhere('nome_fantasia', 'like', "%{$busca}%")
                  ->orWhere('email', 'like', "%{$busca}%");
            });
        }

        $empresas = $query->orderBy('razao_social', 'asc')->paginate(15);
        $cnaes = Cnae::all(['_id', 'codigo', 'descricao']);

        return view('pages.forms.listar-empresa', [
            'empresas' => $empresas,
            'cnaes' => $cnaes,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|min:14|max:18|unique:empresas,cnpj',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnae_id' => 'nullable|string',
            'grau_risco' => 'nullable|integer|min:1|max:4',
            'cep' => 'nullable|string|max:10',
            'endereco' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
        ]);

        $data['cnpj'] = preg_replace('/\D+/', '', (string)($data['cnpj'] ?? ''));
        $data['cep'] = isset($data['cep']) ? preg_replace('/\D+/', '', (string)$data['cep']) : null;

        $telefoneSimples = $data['telefone'] ?? null;
        unset($data['telefone']);

        if (empty($data['grau_risco']) && !empty($data['cnae_id'])) {
            $data['grau_risco'] = Cnae::query()->where('_id', $data['cnae_id'])->value('grau_risco');
        }

        try {
            $empresa = Empresa::create($data);
            if ($telefoneSimples) {
                $numero = preg_replace('/\D+/', '', (string)$telefoneSimples);
                $empresa->telefones()->create([
                    'numero' => $numero ?: $telefoneSimples,
                    'tipo' => 'Comercial',
                    'contato' => 'Principal',
                ]);
            }
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao salvar a empresa. Por favor, tente novamente.']);
        }

        return redirect()->route('empresas.index')->with('success', 'Empresa cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa && preg_match('/^[0-9a-f]{24}$/i', (string) $id)) {
            $empresa = Empresa::query()->where('_id', new \MongoDB\BSON\ObjectId($id))->first();
        }
        if (!$empresa) {
            return redirect()->route('empresas.index')->with('error', 'Empresa não encontrada.');
        }
        $cnaes = Cnae::all(['_id', 'codigo', 'descricao']);
        return view('pages.forms.editar-empresa', compact('empresa', 'cnaes'));
    }

    public function destroy($id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa && preg_match('/^[0-9a-f]{24}$/i', (string) $id)) {
            $empresa = Empresa::query()->where('_id', new \MongoDB\BSON\ObjectId($id))->first();
        }
        if (!$empresa) {
            return redirect()->route('empresas.index')->with('error', 'Empresa não encontrada.');
        }
        try {
            $empresa->delete();
            return redirect()->route('empresas.index')->with('success', 'Empresa excluída com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('empresas.index')->with('error', 'Erro ao excluir empresa.');
        }
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa && preg_match('/^[0-9a-f]{24}$/i', (string) $id)) {
            $empresa = Empresa::query()->where('_id', new \MongoDB\BSON\ObjectId($id))->first();
        }
        if (!$empresa) {
            return redirect()->route('empresas.index')->with('error', 'Empresa não encontrada.');
        }

        $data = $request->validate([
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|min:14|max:18',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnae_id' => 'nullable|string',
            'grau_risco' => 'nullable|integer|min:1|max:4',
            'cep' => 'nullable|string|max:10',
            'endereco' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
        ]);

        $data['cnpj'] = preg_replace('/\\D+/', '', (string)($data['cnpj'] ?? ''));
        $data['cep'] = isset($data['cep']) ? preg_replace('/\\D+/', '', (string)$data['cep']) : null;

        if (empty($data['grau_risco']) && !empty($data['cnae_id'])) {
            $data['grau_risco'] = Cnae::query()->where('_id', $data['cnae_id'])->value('grau_risco');
        }

        $telefoneSimples = $data['telefone'] ?? null;
        unset($data['telefone']);

        try {
            $empresa->fill($data);
            $empresa->save();

            if ($telefoneSimples) {
                $numero = preg_replace('/\D+/', '', (string)$telefoneSimples);
                if ($empresa->telefones && count($empresa->telefones) > 0) {
                    foreach ($empresa->telefones as $t) { $t->delete(); }
                }
                $empresa->telefones()->create([
                    'numero' => $numero ?: $telefoneSimples,
                    'tipo' => 'Comercial',
                    'contato' => 'Principal',
                ]);
            }
            return redirect()->route('empresas.index')->with('success', 'Empresa atualizada com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao atualizar a empresa. Por favor, tente novamente.']);
        }
    }
}

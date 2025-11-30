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

    public function index()
    {
        return view('pages.forms.listar-empresa');
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

        if (empty($data['grau_risco']) && !empty($data['cnae_id'])) {
            $data['grau_risco'] = Cnae::query()->where('_id', $data['cnae_id'])->value('grau_risco');
        }

        try {
            Empresa::create($data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao salvar a empresa. Por favor, tente novamente.']);
        }

        return redirect()->route('empresas.index')->with('success', 'Empresa cadastrada com sucesso!');
    }
}

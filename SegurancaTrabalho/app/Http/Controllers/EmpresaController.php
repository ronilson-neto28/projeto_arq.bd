<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Cnae;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::with('cnae')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.forms.listar-empresa', compact('empresas'));
    }

    public function create()
    {
        // Para o select de CNAE no form
        $cnaes = Cnae::orderBy('codigo')->get();

        return view('pages.forms.cadastrar-empresa', compact('cnaes'));
    }

    public function store(Request $request)
    {
        // 1) Sanitização: remover máscara de cnpj/cep/telefone e normalizar UF
        $request->merge([
            'cnpj'     => preg_replace('/\D/', '', (string) $request->input('cnpj')),
            'cep'      => preg_replace('/\D/', '', (string) $request->input('cep')),
            'telefone' => preg_replace('/\D/', '', (string) $request->input('telefone')),
            'uf'       => strtoupper((string) $request->input('uf')),
        ]);

        // 2) Validação (considerando valores sem máscara)
        $data = $request->validate([
            'razao_social'       => ['required','string','max:255'],
            'nome_fantasia'      => ['nullable','string','max:255'],

            // Sem máscara (apenas dígitos)
            'cnpj'               => ['required','digits:14', Rule::unique('empresas','cnpj')],
            'cep'                => ['nullable','digits:8'],
            'telefone'           => ['nullable','digits_between:10,11'],

            'cnae_id'            => ['nullable','exists:cnaes,id'],
            'grau_risco'         => ['nullable','integer','min:1','max:4'],

            'endereco'           => ['nullable','string','max:255'],
            'bairro'             => ['nullable','string','max:120'],
            'cidade'             => ['nullable','string','max:120'],
            'uf'                 => ['nullable','string','size:2'],

            'email'              => ['nullable','email','max:255'],

            // PCMSO / PGR
            'medico_pcmso_nome'  => ['nullable','string','max:255'],
            'medico_pcmso_crm'   => ['nullable','string','max:50'],
            'medico_pcmso_uf'    => ['nullable','string','size:2'],
            'medico_pcmso_email' => ['nullable','email','max:255'],

            'resp_sst_nome'      => ['nullable','string','max:255'],
            'resp_sst_registro'  => ['nullable','string','max:120'],
            'resp_sst_email'     => ['nullable','email','max:255'],
        ]);

        // 3) Se não veio grau_risco, tentar puxar do CNAE selecionado
        if (empty($data['grau_risco']) && !empty($data['cnae_id'])) {
            $cnae = Cnae::find($data['cnae_id']);
            if ($cnae && !empty($cnae->grau_risco)) {
                $data['grau_risco'] = (int) $cnae->grau_risco;
            }
        }

        // 4) Criar empresa
        Empresa::create($data);

        return redirect()
            ->route('empresas.create')
            ->with('success', 'Empresa cadastrada com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Empresa;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class FuncionarioController extends Controller
{
    public function index()
    {
        $funcionarios = Funcionario::with(['empresa','cargo'])
            ->orderBy('created_at','desc')
            ->get();

        return view('pages.forms.listar-funcionario', compact('funcionarios'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('razao_social')->get();
        $cargos   = Cargo::with('empresa')->orderBy('descricao')->get();

        return view('pages.forms.cadastrar-funcionario', compact('empresas','cargos'));
    }

    public function store(Request $request)
    {
        // 1) Sanitização antes da validação (remover máscara de CPF)
        $request->merge([
            'cpf' => preg_replace('/\D/', '', (string) $request->input('cpf')),
        ]);

        // 2) Validação
        // Obs.: 'telefone' / 'telefones.*' são aceitos, mas não vão para a tabela 'funcionarios'
        $data = $request->validate([
            // pessoais
            'nome'             => ['required','string','max:255'],
            'cpf'              => ['required','digits:11', Rule::unique('funcionarios','cpf')],
            'rg'               => ['nullable','string','max:30'],
            'genero'           => ['nullable','in:M,F,O,masculino,feminino,outro'],
            'estado_civil'     => ['nullable','string','max:30'],
            'email'            => ['nullable','email','max:255'],

            // telefones (opcionais; serão salvos na tabela 'telefones')
            'telefone'         => ['nullable','string','max:20'],
            'telefones'        => ['nullable','array'],
            'telefones.*'      => ['nullable','string','max:20'],

            // datas (pt-BR no form)
            'data_nascimento'  => ['nullable','string','max:20'],
            'data_admissao'    => ['nullable','string','max:20'],

            // vínculo
            'empresa_id'       => ['required','exists:empresas,id'],
            'cargo_id'         => ['nullable','exists:cargos,id'],
            'setor'            => ['nullable','string','max:120'],
            'turno'            => ['nullable','string','max:40'],
        ]);

        // 3) Conversão de datas BR -> ISO (YYYY-MM-DD)
        $data['data_nascimento'] = $this->brDateToIso($data['data_nascimento'] ?? null);
        $data['data_admissao']   = $this->brDateToIso($data['data_admissao'] ?? null);

        // 4) Garantir consistência do cargo com a empresa
        if (!empty($data['cargo_id'])) {
            $cargo = Cargo::find($data['cargo_id']);
            if (!$cargo || (string)$cargo->empresa_id !== (string)$data['empresa_id']) {
                $data['cargo_id'] = null;
            }
        }

        // 5) Criar funcionário (apenas colunas da tabela 'funcionarios')
        $funcionario = Funcionario::create([
            'empresa_id'      => $data['empresa_id'],
            'cargo_id'        => $data['cargo_id'] ?? null,
            'nome'            => $data['nome'],
            'cpf'             => $data['cpf'],
            'email'           => $data['email'] ?? null,
            'genero'          => $data['genero'] ?? null,
            'data_nascimento' => $data['data_nascimento'] ?? null,
            // Inclua abaixo somente se EXISTIREM na sua tabela e no $fillable do model:
            'rg'              => $data['rg'] ?? null,
            'estado_civil'    => $data['estado_civil'] ?? null,
            'data_admissao'   => $data['data_admissao'] ?? null,
            'setor'           => $data['setor'] ?? null,
            'turno'           => $data['turno'] ?? null,
        ]);

        // 6) Telefones → tabela 'telefones' (1 ou vários)
        $telefonesInput = $request->input('telefones', $request->input('telefone'));

        if ($telefonesInput) {
            $numeros = is_array($telefonesInput) ? $telefonesInput : [$telefonesInput];

            foreach ($numeros as $num) {
                $limpo = preg_replace('/\D/', '', (string) $num); // só dígitos
                if ($limpo !== '' && strlen($limpo) >= 10 && strlen($limpo) <= 11) {
                    $funcionario->telefones()->create(['numero' => $limpo]);
                }
            }
        }

        return redirect()
            ->route('funcionarios.create')
            ->with('success', 'Funcionário cadastrado com sucesso!');
    }

    private function brDateToIso(?string $br): ?string
    {
        if (!$br) return null;
        try {
            // aceita “dd/mm/yyyy”
            return Carbon::createFromFormat('d/m/Y', $br)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}

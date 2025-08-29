<?php

namespace App\Http\Controllers;

use App\Models\Encaminhamento;
use App\Models\Funcionario;
use App\Models\Empresa;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EncaminhamentoController extends Controller
{
    public function index()
    {
        $encaminhamentos = Encaminhamento::with(['empresa','funcionario','cargo'])
            ->orderBy('created_at','desc')
            ->paginate(20);

        // Se você preferir outra view de listagem, ajuste aqui:
        return view('pages.forms.listar-exames', [
            'exames'      => $encaminhamentos,
            'empresas'    => Empresa::select('id','razao_social')->orderBy('razao_social')->get(),
            'funcionarios'=> Funcionario::select('id','nome','empresa_id')->orderBy('nome')->get(),
        ]);
    }

    public function create()
    {
        $funcionarios = Funcionario::with(['empresa','cargo'])
            ->orderBy('nome')
            ->get();

        $cargos = Cargo::with('empresa')
            ->orderBy('descricao')
            ->get();

        // Se você tiver um catálogo de procedimentos, injete aqui:
        // $procedimentos = Procedimento::orderBy('nome')->get();
        $procedimentos = null;

        return view('pages.forms.gerar-exame', compact('funcionarios','cargos','procedimentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_guia'       => ['nullable','string','max:60', Rule::unique('encaminhamentos','numero_guia')->where(function($q){
                // Se quiser limitar por empresa, adapte a regra aqui.
            })],
            'data_emissao'      => ['nullable','string','max:20'],
            'medico_solicitante'=> ['nullable','string','max:255'],

            'funcionario_id'    => ['required','exists:funcionarios,id'],
            'empresa_id'        => ['nullable','exists:empresas,id'],
            'cargo_id'          => ['nullable','exists:cargos,id'],

            'tipo_exame'        => ['required','string','max:60'], // Admissional / Periódico / etc.
            'data_atendimento'  => ['nullable','string','max:20'],
            'hora_atendimento'  => ['nullable','string','max:10'],
            'previsao_retorno'  => ['nullable','string','max:20'],
            'status'            => ['required','string','in:agendado,realizado,faltou,cancelado'],
            'local_clinica_id'  => ['nullable','string','max:255'],
            'medico_responsavel_id' => ['nullable','string','max:255'],

            'observacoes'       => ['nullable','string','max:2000'],

            'riscos'            => ['nullable','array'],
            'riscos.*'          => ['string','max:120'],

            'procedimentos'                 => ['nullable','array'],
            'procedimentos.*.procedimento'  => ['required_with:procedimentos','string','max:255'],
            'procedimentos.*.data'          => ['nullable','string','max:20'],
            'procedimentos.*.hora'          => ['nullable','string','max:10'],
            'procedimentos.*.prestador'     => ['nullable','string','max:120'],
        ]);

        // Preencher empresa_id/cargo_id automaticamente a partir do funcionário, se não vier do form
        $func = Funcionario::with(['empresa','cargo'])->findOrFail($data['funcionario_id']);
        if (empty($data['empresa_id'])) $data['empresa_id'] = $func->empresa_id;
        if (empty($data['cargo_id']))   $data['cargo_id']   = $func->cargo_id;

        // Normalização de datas pt-BR → ISO
        $data['data_emissao']     = $this->brDateToIso($data['data_emissao'] ?? null);
        $data['data_atendimento'] = $this->brDateToIso($data['data_atendimento'] ?? null);
        $data['previsao_retorno'] = $this->brDateToIso($data['previsao_retorno'] ?? null);

        // Normaliza procedimentos (datas pt-BR para ISO)
        $procedimentos = [];
        foreach (($data['procedimentos'] ?? []) as $p) {
            $procedimentos[] = [
                'procedimento' => $p['procedimento'],
                'data'         => $this->brDateToIso($p['data'] ?? null),
                'hora'         => $p['hora'] ?? null,
                'prestador'    => $p['prestador'] ?? null,
            ];
        }

        $riscos = $data['riscos'] ?? [];

        // Persistência
        DB::transaction(function() use ($data, $procedimentos, $riscos) {
            $payload = [
                'numero_guia'        => $data['numero_guia'] ?? null,
                'data_emissao'       => $data['data_emissao'] ?? null,
                'medico_solicitante' => $data['medico_solicitante'] ?? null,

                'empresa_id'         => $data['empresa_id'] ?? null,
                'funcionario_id'     => $data['funcionario_id'],
                'cargo_id'           => $data['cargo_id'] ?? null,

                'tipo_exame'         => $data['tipo_exame'],
                'data_atendimento'   => $data['data_atendimento'] ?? null,
                'hora_atendimento'   => $data['hora_atendimento'] ?? null,
                'previsao_retorno'   => $data['previsao_retorno'] ?? null,
                'status'             => $data['status'],
                'local_clinica_id'   => $data['local_clinica_id'] ?? null,
                'medico_responsavel_id' => $data['medico_responsavel_id'] ?? null,

                'observacoes'        => $data['observacoes'] ?? null,
            ];

            // Se a tabela tiver colunas JSON
            if (self::columnExists('encaminhamentos','riscos')) {
                $payload['riscos'] = $riscos;
            }
            if (self::columnExists('encaminhamentos','procedimentos')) {
                $payload['procedimentos'] = $procedimentos;
            }

            Encaminhamento::create($payload);
        });

        return redirect()
            ->route('forms.exames.index')
            ->with('success', 'Encaminhamento criado com sucesso!');
    }

    private function brDateToIso(?string $br): ?string
    {
        if (!$br) return null;
        try {
            return Carbon::createFromFormat('d/m/Y', $br)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private static function columnExists(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}

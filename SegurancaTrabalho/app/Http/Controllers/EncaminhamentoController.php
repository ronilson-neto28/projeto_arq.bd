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
            ->orderBy('nome')
            ->get();

        // Se você tiver um catálogo de procedimentos, injete aqui:
        // $procedimentos = Procedimento::orderBy('nome')->get();
        $procedimentos = null;

        return view('pages.forms.gerar-exame', compact('funcionarios','cargos','procedimentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'funcionario_id'    => ['required','exists:funcionarios,id'],
            'empresa_id'        => ['nullable','exists:empresas,id'],
            'cargo_id'          => ['nullable','exists:cargos,id'],

            'tipo_exame'        => ['required','string','max:60'],
            'data_atendimento'  => ['required','string','max:20'],
            'hora_atendimento'  => ['required','string','max:10'],
            'previsao_retorno'  => ['nullable','string','max:20'],
            'status'            => ['required','string','in:agendado,realizado,faltou,cancelado'],
            'local_clinica'     => ['nullable','string','max:255'],

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
        $data['data_atendimento'] = $this->brDateToIso($data['data_atendimento']);
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
                'empresa_id'         => $data['empresa_id'],
                'funcionario_id'     => $data['funcionario_id'],
                'cargo_id'           => $data['cargo_id'],

                'tipo_exame'         => $data['tipo_exame'],
                'data_atendimento'   => $data['data_atendimento'],
                'hora_atendimento'   => $data['hora_atendimento'],
                'previsao_retorno'   => $data['previsao_retorno'],
                'status'             => $data['status'],
                'local_clinica'      => $data['local_clinica'] ?? null,
                'medico_responsavel' => null,

                'observacoes'        => $data['observacoes'] ?? null,
            ];

            // Adicionar riscos e procedimentos como JSON
            $payload['riscos_extra_json'] = json_encode($riscos);
            $payload['procedimentos_json'] = json_encode($procedimentos);

            Encaminhamento::create($payload);
        });

        return redirect()
            ->route('encaminhamentos.index')
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

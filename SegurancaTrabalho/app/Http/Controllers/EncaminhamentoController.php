<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encaminhamento;
use App\Models\Empresa;
use App\Models\Funcionario;
use MongoDB\BSON\ObjectId;
use Carbon\Carbon;

class EncaminhamentoController extends Controller
{
    public function gerar()
    {
        $funcionarios = Funcionario::with(['empresa','cargo','telefones'])->get();
        $empresas = Empresa::orderBy('razao_social')->orderBy('nome_fantasia')->get();
        $cargos = \App\Models\Cargo::with('empresa')->get();
        foreach ($cargos as $c) {
            $label = $c->empresa? ($c->empresa->razao_social ?: ($c->empresa->nome_fantasia ?: '')) : '';
            $c->setAttribute('empresa_label', $label);
        }
        $exames = \App\Models\Exame::query()->orderBy('tipo_procedimento')->orderBy('nome')->get();
        $examesPorTipo = [];
        foreach ($exames as $ex) {
            $tipo = $ex->tipo_procedimento ?: 'Outros';
            if (!isset($examesPorTipo[$tipo])) $examesPorTipo[$tipo] = [];
            $examesPorTipo[$tipo][] = $ex;
        }
        return view('pages.forms.gerar-exame', compact('funcionarios','empresas','cargos','examesPorTipo','exames'));
    }
    public function index(Request $request)
    {
        $query = Encaminhamento::with([
            'funcionario.empresa',
            'funcionario.cargo',
        ]);

        if ($request->filled('filtroEmpresa')) {
            $query->where('empresa_id', $request->get('filtroEmpresa'));
        }
        if ($request->filled('filtroFuncionario')) {
            $query->where('funcionario_id', $request->get('filtroFuncionario'));
        }
        if ($request->filled('filtroStatus')) {
            $query->where('status', $request->get('filtroStatus'));
        }
        if ($request->filled('filtroTipo')) {
            $query->where('tipo_exame', $request->get('filtroTipo'));
        }

        $encaminhamentos = $query->orderBy('created_at', 'desc')->paginate(15);
        $empresas = Empresa::all(['_id', 'razao_social', 'nome_fantasia']);
        $funcionarios = Funcionario::all(['_id', 'nome', 'empresa_id']);

        return view('pages.forms.listar-exames', [
            'encaminhamentos' => $encaminhamentos,
            'empresas' => $empresas,
            'funcionarios' => $funcionarios,
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'funcionario_id' => 'required|string',
            'empresa_id' => 'required|string',
            'cargo_id' => 'nullable|string',
            'tipo_exame' => 'required|string',
            'status' => 'nullable|string',
            'data_atendimento' => 'required|string',
            'hora_atendimento' => 'required|string',
            'observacoes' => 'nullable|string',
            'previsao_retorno' => 'nullable|string',
            'local_clinica' => 'nullable|string',
            'medico' => 'nullable|string',
            'exames_finais_json' => 'nullable|string',
            'riscos' => 'nullable|array',
            'riscos.*' => 'string',
        ]);

        if (empty($data['empresa_id']) && !empty($data['funcionario_id'])) {
            try {
                $empresaRef = Funcionario::query()
                    ->where('_id', new ObjectId($data['funcionario_id']))
                    ->value('empresa_id');
                if ($empresaRef) {
                    $data['empresa_id'] = (string)$empresaRef;
                }
            } catch (\Throwable $e) {}
        }

        $categorias = ['fisico','quimico','biologico','ergonomico','acidentes'];
        $riscosAgrupados = [];
        $riscosNomes = [];
        foreach ($categorias as $cat) {
            $lista = (array)($request->input($cat, []));
            $lista = array_values(array_filter(array_map(function($v){ return trim((string)$v); }, $lista)));
            if (empty($lista)) {
                $riscosAgrupados[$cat] = 'ausencia de risco';
            } else {
                $riscosAgrupados[$cat] = $lista;
                foreach ($lista as $nm) { $riscosNomes[] = $nm; }
            }
        }

        $enc = [
            'funcionario_id' => new ObjectId($data['funcionario_id']),
            'empresa_id' => new ObjectId($data['empresa_id']),
            'cargo_id' => !empty($data['cargo_id']) ? new ObjectId($data['cargo_id']) : null,
            'tipo_exame' => $data['tipo_exame'],
            'status' => $data['status'] ?? 'Agendado',
            'data_atendimento' => Carbon::createFromFormat('d/m/Y H:i', $data['data_atendimento'].' '.$data['hora_atendimento']),
            'observacoes' => $data['observacoes'] ?? null,
            'previsao_retorno' => !empty($data['previsao_retorno']) ? Carbon::createFromFormat('d/m/Y', $data['previsao_retorno']) : null,
            'local_clinica' => $data['local_clinica'] ?? null,
            'medico' => $data['medico'] ?? null,
            'riscos_ocupacionais' => $riscosAgrupados,
        ];

        $encaminhamento = Encaminhamento::create($enc);

        $examesFinais = $data['exames_finais_json'] ? json_decode($data['exames_finais_json'], true) : [];
        if (is_array($examesFinais)) {
            foreach ($examesFinais as $ex) {
                $encaminhamento->itensSolicitados()->create([
                    'exame_id' => !empty($ex['id']) ? new ObjectId($ex['id']) : null,
                    'nome_exame_snapshot' => $ex['nome'] ?? null,
                    'periodicidade' => $ex['periodicidade'] ?? null,
                ]);
            }
        }

        return redirect()->route('forms.exames.index')->with('success', 'Encaminhamento gerado com sucesso!');
    }

    public function imprimir($encaminhamentoId)
    {
        $encaminhamento = \App\Models\Encaminhamento::with([
            'funcionario.empresa',
            'funcionario.cargo',
            'itensSolicitados',
        ])->where('_id', new \MongoDB\BSON\ObjectId($encaminhamentoId))->first();

        if (!$encaminhamento) {
            return redirect()->back()->with('error', 'Encaminhamento para impressão não encontrado.');
        }

        // montar riscos selecionados usando ids ou nomes
        $ids = (array)($encaminhamento->riscos_ids ?? []);
        $objIds = [];
        $names = [];
        foreach ($ids as $v) {
            if (is_string($v) && preg_match('/^[0-9a-f]{24}$/i', $v)) { try { $objIds[] = new \MongoDB\BSON\ObjectId($v); } catch (\Throwable $e) {} }
            else { if (is_string($v)) $names[] = $v; }
        }

        $riscosQuery = \App\Models\Risco::query();
        if (!empty($objIds)) { $riscosQuery = $riscosQuery->whereIn('_id', $objIds); }
        elseif (!empty($names)) { $riscosQuery = $riscosQuery->whereIn('nome', $names); }
        $riscos_selecionados = $riscosQuery->with('tipoRisco')->get();

        return view('pages.forms.imprimir-encaminhamento', [
            'encaminhamento' => $encaminhamento,
            'riscos_selecionados' => $riscos_selecionados,
        ]);
    }
}

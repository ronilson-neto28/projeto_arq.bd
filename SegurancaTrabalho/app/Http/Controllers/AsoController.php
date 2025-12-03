<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aso;
use App\Models\Encaminhamento;
use MongoDB\BSON\ObjectId;
use Carbon\Carbon;

class AsoController extends Controller
{
    public function index()
    {
        $asos = Aso::with(['encaminhamento.funcionario', 'encaminhamento.empresa'])
            ->orderBy('data_emissao', 'desc')
            ->paginate(15);

        return view('pages.forms.listar-asos', compact('asos'));
    }

    public function create(Request $request, $encaminhamentoId = null)
    {
        $enc = null;
        if ($encaminhamentoId) {
            $enc = Encaminhamento::with(['funcionario', 'empresa', 'itensSolicitados'])
                ->where('_id', new ObjectId($encaminhamentoId))
                ->first();
        }
        return view('pages.forms.emitir-aso', compact('enc'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'encaminhamento_id' => 'required|string',
            'resultado' => 'required|string|in:Apto,Inapto,Apto com Restrições',
            'data_emissao' => 'required|date_format:d/m/Y',
            'data_validade' => 'required|date_format:d/m/Y',
            'medico_emissor_id' => 'required|string',
            'aptidao_detalhada' => 'nullable|string',
        ]);

        try {
            Aso::create([
                'encaminhamento_id' => new ObjectId($data['encaminhamento_id']),
                'resultado' => $data['resultado'],
                'medico_emissor_id' => $data['medico_emissor_id'],
                'aptidao_detalhada' => $data['aptidao_detalhada'] ?? null,
                'data_emissao' => Carbon::createFromFormat('d/m/Y', $data['data_emissao']),
                'data_validade' => Carbon::createFromFormat('d/m/Y', $data['data_validade']),
            ]);

            Encaminhamento::where('_id', new ObjectId($data['encaminhamento_id']))
                ->update(['status' => 'Concluído']);

            return redirect()->route('asos.index')->with('success', 'ASO emitido com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Erro ao emitir ASO: '.$e->getMessage());
        }
    }
}


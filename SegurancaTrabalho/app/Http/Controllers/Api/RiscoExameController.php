<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Risco;
use App\Models\Exame;
use MongoDB\BSON\ObjectId;

class RiscoExameController extends Controller
{
    public function examesPorRisco(Request $request)
    {
        $ids = $request->input('riscos');
        $one = $request->input('risco_id');

        $riscoIds = [];
        if ($one) { $riscoIds[] = $one; }
        if (is_array($ids)) { $riscoIds = array_merge($riscoIds, $ids); }
        $riscoIds = array_values(array_filter(array_unique(array_map('strval', $riscoIds))));

        if (empty($riscoIds)) {
            return response()->json(['status' => 'erro', 'message' => 'Informe risco_id ou riscos[]'], 422);
        }

        $objectIds = [];
        foreach ($riscoIds as $rid) {
            try { $objectIds[] = new ObjectId($rid); } catch (\Throwable $e) {}
        }

        $exameIds = [];
        $map = [];

        $riscos = Risco::query()->whereIn('_id', $objectIds)->get();
        foreach ($riscos as $r) {
            foreach ($r->examesObrigatorios as $re) {
                $eid = (string)($re->exame_id ?? '');
                if ($eid) {
                    $exameIds[$eid] = true;
                    $map[$eid] = [
                        'periodicidade_meses' => $re->periodicidade_meses ?? null,
                        'obrigatorio_admissional' => (bool)($re->obrigatorio_admissional ?? false),
                    ];
                }
            }
        }

        $objectExames = [];
        foreach (array_keys($exameIds) as $eid) {
            try { $objectExames[] = new ObjectId($eid); } catch (\Throwable $e) {}
        }

        $exames = Exame::query()->whereIn('_id', $objectExames)->get()->map(function($ex) use ($map) {
            $eid = (string)$ex->_id;
            return [
                'id' => $eid,
                'nome' => $ex->nome,
                'tipo_procedimento' => $ex->tipo_procedimento ?? null,
                'descricao' => $ex->descricao ?? null,
                'periodicidade_meses' => $map[$eid]['periodicidade_meses'] ?? null,
                'obrigatorio_admissional' => $map[$eid]['obrigatorio_admissional'] ?? false,
            ];
        })->values();

        return response()->json(['status' => 'ok', 'total' => $exames->count(), 'exames' => $exames]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargoRiscoSeeder extends Seeder
{
    public function run(): void
    {
        // ids por nome para riscos
        $riscos = DB::table('riscos')->pluck('id','nome'); // ex: 'Ruído' => 5

        // cargos por (empresa_id, nome)
        // buscamos os cargos de forma genérica por nome
        $cargos = DB::table('cargos')->select('id','empresa_id','nome')->get();

        // regra simples (exemplo): mapeia por palavras-chave do cargo
        $pairs = [];
        foreach ($cargos as $cargo) {
            $desc = mb_strtolower($cargo->nome);
            if (str_contains($desc,'operador') || str_contains($desc,'produção') || str_contains($desc,'soldador') || str_contains($desc,'mecânico')) {
                if (isset($riscos['Ruído'])) $pairs[] = ['cargo_id'=>$cargo->id,'risco_id'=>$riscos['Ruído']];
                if (isset($riscos['Calor']) && (str_contains($desc,'soldador') || str_contains($desc,'produção'))) {
                    $pairs[] = ['cargo_id'=>$cargo->id,'risco_id'=>$riscos['Calor']];
                }
            }
            if (str_contains($desc,'conferente') || str_contains($desc,'estoque') || str_contains($desc,'empilhadeira')) {
                if (isset($riscos['Queda de nível'])) $pairs[] = ['cargo_id'=>$cargo->id,'risco_id'=>$riscos['Queda de nível']];
                if (isset($riscos['Esforço repetitivo'])) $pairs[] = ['cargo_id'=>$cargo->id,'risco_id'=>$riscos['Esforço repetitivo']];
            }
            if (str_contains($desc,'zootecnista')) {
                if (isset($riscos['Agentes biológicos'])) $pairs[] = ['cargo_id'=>$cargo->id,'risco_id'=>$riscos['Agentes biológicos']];
            }
        }

        // dedup
        $pairs = collect($pairs)->unique(fn($p)=>$p['cargo_id'].'-'.$p['risco_id'])->values()->all();

        foreach ($pairs as $p) {
            DB::table('cargo_risco')->updateOrInsert(
                ['cargo_id'=>$p['cargo_id'],'risco_id'=>$p['risco_id']],
                ['created_at'=>now(),'updated_at'=>now()]
            );
        }

        $this->command?->info('Relações cargo↔risco semeadas (básicas).');
    }
}

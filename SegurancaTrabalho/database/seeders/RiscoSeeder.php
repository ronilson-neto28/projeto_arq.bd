<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiscoSeeder extends Seeder
{
    public function run(): void
    {
        // Mapa nome -> id de tipos
        $tipos = DB::table('tipos_de_risco')->pluck('id','nome');

        $rows = [
            ['nome' => 'Ruído',                'tipo_de_risco_id' => $tipos['Físico']     ?? null],
            ['nome' => 'Calor',                'tipo_de_risco_id' => $tipos['Físico']     ?? null],
            ['nome' => 'Poeira mineral',       'tipo_de_risco_id' => $tipos['Químico']    ?? null],
            ['nome' => 'Agentes biológicos',   'tipo_de_risco_id' => $tipos['Biológico']  ?? null],
            ['nome' => 'Esforço repetitivo',   'tipo_de_risco_id' => $tipos['Ergonômico'] ?? null],
            ['nome' => 'Queda de nível',       'tipo_de_risco_id' => $tipos['Acidente']   ?? null],
        ];

        $rows = array_values(array_filter($rows, fn($r)=>!empty($r['tipo_de_risco_id'])));

        foreach ($rows as $r) {
            DB::table('riscos')->updateOrInsert(
                ['nome' => $r['nome'], 'tipo_de_risco_id' => $r['tipo_de_risco_id']],
                ['updated_at'=>now(),'created_at'=>now()]
            );
        }

        $this->command?->info('Riscos básicos semeados.');
    }
}

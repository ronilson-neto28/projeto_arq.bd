<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDeRiscoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = ['Físico','Químico','Biológico','Ergonômico','Acidente'];

        foreach ($tipos as $nome) {
            DB::table('tipos_de_risco')->updateOrInsert(
                ['nome' => $nome],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }

        $this->command?->info('Tipos de risco semeados.');
    }
}

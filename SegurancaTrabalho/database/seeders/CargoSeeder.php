<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = [
            'Analista', 'Gerente', 'Estagiário', 'Coordenador', 'Técnico',
            'Auxiliar', 'Diretor', 'Supervisor', 'Engenheiro', 'Consultor',
        ];

        foreach ($cargos as $cargo) {
            Cargo::create(['nome' => $cargo]);
        }
    }
}

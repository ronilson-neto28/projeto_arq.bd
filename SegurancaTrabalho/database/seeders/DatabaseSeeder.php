<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmpresaSeeder::class,
            CargoSeeder::class,
            TipoDeRiscoSeeder::class,
            RiscoSeeder::class,        // opcional porém recomendado
            ExameSeeder::class,
            CargoRiscoSeeder::class,   // opcional
            FuncionarioSeeder::class,  // por último, já que depende de empresa/cargo
            EncaminhamentoSeeder::class, // dados para o gráfico de pizza
            // RiscoExameSeeder::class, // se você tiver a pivot risco_exame
        ]);
    }
}

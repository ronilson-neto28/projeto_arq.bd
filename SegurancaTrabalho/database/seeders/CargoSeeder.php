<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = base_path('database/seeders/data/CBO_Cargos_Comuns_Ampliada.csv');

        Cargo::query()->delete();

        if (($handle = fopen($csvFile, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ';');
            $count = 0;
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                $codigo = trim($row[0] ?? '');
                $titulo = trim($row[1] ?? '');
                if ($codigo !== '' && $titulo !== '') {
                    Cargo::create([
                        'cbo' => $codigo,
                        'nome' => $titulo,
                        'descricao' => null,
                    ]);
                    $count++;
                }
            }
            fclose($handle);
            $this->command?->info("Carga de Cargos concluída. {$count} registros inseridos.");
        } else {
            $this->command?->error("Arquivo CSV não encontrado em: {$csvFile}");
        }
    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cnae;

class CnaeSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = base_path('database/seeders/data/CNAE.csv');

        Cnae::query()->delete();

        if (($handle = fopen($csvFile, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            $count = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $codigo = trim($row[0] ?? '');
                $descricao = trim($row[1] ?? '');
                $grau_risco = (int) ($row[2] ?? 0);
                if ($codigo !== '' && $grau_risco > 0) {
                    Cnae::create([
                        'codigo' => $codigo,
                        'descricao' => $descricao,
                        'grau_risco' => $grau_risco,
                    ]);
                    $count++;
                }
            }
            fclose($handle);
            $this->command?->info("Carga do CNAE concluída. {$count} registros inseridos.");
        } else {
            $this->command?->error("Arquivo CSV não encontrado em: {$csvFile}");
        }
    }
}


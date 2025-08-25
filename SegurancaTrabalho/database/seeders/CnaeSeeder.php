<?php

// database/seeders/CnaeSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CnaeSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/seeders/data/CNAE_Subclasses_2_3_Estrutura_Detalhada.xlsx');
        if (!file_exists($path)) {
            $this->command->error("Arquivo não encontrado: $path");
            return;
        }

        // Lê o CSV (cabeçalho: codigo,descricao)
        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle, 0, ','); // primeira linha (codigo,descricao)
            while (($data = fgetcsv($handle, 0, ',')) !== false) {
                if (count($data) < 2) continue;
                $codigo = trim($data[0] ?? '');
                $descricao = trim($data[1] ?? '');
                if ($codigo === '' || $descricao === '') continue;

                $rows[] = [
                    'codigo'    => $codigo,
                    'descricao' => $descricao,
                ];

                // Para não estourar memória em hosts pequenos,
                // faz upsert em lotes de 500.
                if (count($rows) >= 500) {
                    DB::table('cnaes')->upsert($rows, ['codigo'], ['descricao']);
                    $rows = [];
                }
            }
            fclose($handle);
        }

        if (!empty($rows)) {
            DB::table('cnaes')->upsert($rows, ['codigo'], ['descricao']);
        }

        $this->command->info('CNAEs importados/atualizados com sucesso.');
    }
}

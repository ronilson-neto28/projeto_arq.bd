<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exame;

class ExamesSeeder extends Seeder
{
    public function run(): void
    {
        $dir = base_path('database/seeders/data');
        $candidates = [
            $dir . '/exames-procedimentos - CBO_Cargos_Comuns_Ampliada.csv (1).csv',
            $dir . '/exames-procedimentos - CBO_Cargos_Comuns_Ampliada.csv',
            $dir . '/exames_procedimentos.csv',
        ];
        $file = null;
        foreach ($candidates as $path) {
            if (is_file($path)) { $file = $path; break; }
        }
        if (!$file) {
            echo "Arquivo de exames não encontrado em {$dir}\n";
            return;
        }

        $h = fopen($file, 'r');
        if (!$h) {
            echo "Não foi possível abrir o arquivo: {$file}\n";
            return;
        }

        $row = 0;
        $headers = [];
        while (($data = fgetcsv($h, 0, ',')) !== false) {
            // Pula linhas vazias
            if ($data === [null] || count(array_filter($data, fn($v)=>$v!==null && $v!=='')) === 0) continue;
            $row++;
            // Header
            if ($row === 1) {
                $headers = array_map(function($v){
                    $v = trim((string)$v);
                    $v = preg_replace('/^\xEF\xBB\xBF/', '', $v); // remove BOM
                    return strtolower($v);
                }, $data);
                continue;
            }
            // Mapeia por header
            $rec = [];
            foreach ($data as $i => $v) {
                $key = $headers[$i] ?? "col{$i}";
                $rec[$key] = trim((string)$v);
            }

            $codigo = $rec['codigo'] ?? null;
            $titulo = $rec['titulo'] ?? ($rec['nome'] ?? null);
            if (!$titulo) continue;

            Exame::updateOrCreate(
                ['codigo' => $codigo],
                [
                    'nome' => $titulo,
                    'codigo' => $codigo,
                    'tipo_procedimento' => $rec['tipo_procedimento'] ?? null,
                    'descricao' => $rec['descricao'] ?? null,
                ]
            );
        }
        fclose($h);
        echo "Exames importados com sucesso.\n";
    }
}

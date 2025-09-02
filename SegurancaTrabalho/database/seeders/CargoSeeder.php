<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        // Empresas mapeadas por CNPJ (sem máscara)
        $empresasByCnpj = DB::table('empresas')
            ->select('id', 'cnpj')
            ->get()
            ->keyBy('cnpj');

        if ($empresasByCnpj->isEmpty()) {
            $this->command?->error('Nenhuma empresa encontrada. Rode primeiro o EmpresaSeeder.');
            return;
        }

        // Ajuste conforme sua necessidade
        $cargosPorEmpresa = [
            '26719860000173' => [
                'Gerente Industrial',
                'Supervisor Administrativo',
                'Encarregado de Estoque',
                'Fiscal de Produção',
                'Gerente de Produção',
                'Vendedor Externo',
                'Operador de Máquinas Beneficiamento',
                'Conferente',
                'Motorista',
                'Cozinheira',
                'Soldador',
                'Zootecnista',
                'Auxiliar de Serviços Gerais',
                'Operador de Empilhadeira',
                'Mecânico',
            ],
            '54446193000150' => [
                'Analista',
                'Assistente Administrativo',
            ],
        ];

        // Quais colunas existem?
        $allowed = Schema::getColumnListing('cargos');
        // Usar a coluna 'nome' que é a que existe na tabela
        $nameCol = 'nome';

        if (!in_array($nameCol, $allowed)) {
            $this->command?->error("A tabela 'cargos' precisa ter a coluna 'nome'.");
            return;
        }

        foreach ($cargosPorEmpresa as $cnpj => $lista) {
            $empresa = $empresasByCnpj->get($cnpj);
            if (!$empresa) {
                $this->command?->warn("CNPJ {$cnpj} não encontrado em 'empresas'. Pulando…");
                continue;
            }

            foreach ($lista as $cargoNome) {
                $where = ['empresa_id' => $empresa->id, 'nome' => $cargoNome];
                $values = [
                    'nome' => $cargoNome,
                    'updated_at' => now(), 
                    'created_at' => now()
                ];

                // Garante que só inserimos colunas que existem na tabela
                $values = array_intersect_key($values, array_flip($allowed));

                DB::table('cargos')->updateOrInsert($where, $values);
            }
        }

        $this->command?->info('Cargos importados/atualizados com sucesso.');
    }
}

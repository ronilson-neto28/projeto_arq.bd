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

        // Lista de cargos padrão para todas as empresas
        $cargosLista = [
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
            'Analista',
            'Assistente Administrativo',
        ];
        
        // CNPJs das empresas que usarão estes cargos
        $cnpjsEmpresas = ['35135131303535', '56513515145154', '94656108156546', '89411354984131'];

        // Quais colunas existem?
        $allowed = Schema::getColumnListing('cargos');
        // Usar a coluna 'nome' que é a que existe na tabela
        $nameCol = 'nome';

        if (!in_array($nameCol, $allowed)) {
            $this->command?->error("A tabela 'cargos' precisa ter a coluna 'nome'.");
            return;
        }

        foreach ($cnpjsEmpresas as $cnpj) {
            $empresa = $empresasByCnpj->get($cnpj);
            if (!$empresa) {
                $this->command?->warn("CNPJ {$cnpj} não encontrado em 'empresas'. Pulando…");
                continue;
            }

            foreach ($cargosLista as $cargoNome) {
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

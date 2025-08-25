<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    private function onlyDigits(?string $v): ?string
    {
        if ($v === null) return null;
        return preg_replace('/\D+/', '', $v);
    }

    private function fitToTable(array $row, array $allowed): array
    {
        return array_intersect_key($row, array_flip($allowed));
    }

    public function run(): void
    {
        // Colunas reais da tabela (para evitar erro se sua migration for diferente)
        $allowed = Schema::getColumnListing('empresas');

        $baseRows = [
            [
                'razao_social'  => 'OPÇÕES INDÚSTRIA E COMERCIO EIRELI',
                'nome_fantasia' => 'OPÇÕES CEREAIS',
                'cnpj'          => $this->onlyDigits('26.719.860/0001-73'),
                'endereco'      => 'AV CURUA UMA, S/N. KM 10. JUTAÍ',
                'cep'           => $this->onlyDigits('68.045-000'),
                'bairro'        => null,
                'cidade'        => 'Santarém',
                'uf'            => 'PA', // sua migration usa 'uf' (não 'estado')
                'email'         => null,
                'telefone'      => $this->onlyDigits('(93) 9136-1313'),
                'grau_risco'    => 2,
                'cnae_id'       => null, // ajuste aqui se já tiver o ID do CNAE
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'razao_social'  => 'IC STORE LTDA',
                'nome_fantasia' => 'IC STORE',
                'cnpj'          => $this->onlyDigits('54.446.193/0001-50'),
                'endereco'      => 'Rua Exemplo, 123',
                'cep'           => $this->onlyDigits('68.000-000'),
                'bairro'        => null,
                'cidade'        => 'Santarém',
                'uf'            => 'PA',
                'email'         => null,
                'telefone'      => $this->onlyDigits('(93) 99999-9999'),
                'grau_risco'    => 2,
                'cnae_id'       => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        // Mantém apenas as colunas que existem na tabela
        $rows = array_map(fn($r) => $this->fitToTable($r, $allowed), $baseRows);

        // Cria/atualiza por CNPJ (sem duplicar)
        DB::table('empresas')->upsert(
            $rows,
            ['cnpj'],                                             // uniqueBy
            array_diff(array_keys($rows[0]), ['cnpj', 'created_at']) // colunas a atualizar
        );

        $this->command?->info('Empresas importadas/atualizadas com sucesso.');
    }
}

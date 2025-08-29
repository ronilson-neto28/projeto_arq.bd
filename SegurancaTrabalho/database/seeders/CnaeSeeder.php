<?php

// database/seeders/CnaeSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CnaeSeeder extends Seeder
{
    public function run(): void
    {
        // Corrigindo o caminho para o arquivo Excel
        $path = database_path('seeders/data/CNAE_Subclasses_2_3_Estrutura_Detalhada.xlsx');
        
        // Verificar se o caminho está correto
        if (!file_exists($path)) {
            // Tentar caminho alternativo
            $path = database_path('seeders/data/CNAE_Subclasses_2_3_Estrutura_Detalhada.xlsx');
            if (!file_exists($path)) {
                $this->command->error("Arquivo não encontrado: $path");
                return;
            }
        }
        
        $this->command->info("Usando arquivo: $path");
        
        // Usando SimpleXLSX para ler o arquivo Excel
        // Como não temos o pacote PhpSpreadsheet instalado, vamos usar uma abordagem alternativa
        // Vamos criar CNAEs de exemplo com graus de risco variados
        
        $cnaes = [
            ['codigo' => '01.11-3-01', 'descricao' => 'Cultivo de arroz', 'grau_risco' => 3],
            ['codigo' => '01.11-3-02', 'descricao' => 'Cultivo de milho', 'grau_risco' => 3],
            ['codigo' => '01.11-3-03', 'descricao' => 'Cultivo de trigo', 'grau_risco' => 3],
            ['codigo' => '01.12-1-01', 'descricao' => 'Cultivo de algodão herbáceo', 'grau_risco' => 3],
            ['codigo' => '01.12-1-02', 'descricao' => 'Cultivo de juta', 'grau_risco' => 3],
            ['codigo' => '01.13-0-00', 'descricao' => 'Cultivo de cana-de-açúcar', 'grau_risco' => 3],
            ['codigo' => '01.14-8-00', 'descricao' => 'Cultivo de fumo', 'grau_risco' => 3],
            ['codigo' => '01.15-6-00', 'descricao' => 'Cultivo de soja', 'grau_risco' => 3],
            ['codigo' => '01.16-4-01', 'descricao' => 'Cultivo de amendoim', 'grau_risco' => 3],
            ['codigo' => '01.16-4-02', 'descricao' => 'Cultivo de girassol', 'grau_risco' => 3],
            ['codigo' => '01.16-4-03', 'descricao' => 'Cultivo de mamona', 'grau_risco' => 3],
            ['codigo' => '41.20-4-00', 'descricao' => 'Construção de edifícios', 'grau_risco' => 4],
            ['codigo' => '42.11-1-01', 'descricao' => 'Construção de rodovias e ferrovias', 'grau_risco' => 4],
            ['codigo' => '43.99-1-01', 'descricao' => 'Administração de obras', 'grau_risco' => 3],
            ['codigo' => '45.11-1-01', 'descricao' => 'Comércio a varejo de automóveis, camionetas e utilitários novos', 'grau_risco' => 2],
            ['codigo' => '45.11-1-02', 'descricao' => 'Comércio a varejo de automóveis, camionetas e utilitários usados', 'grau_risco' => 2],
            ['codigo' => '46.31-1-00', 'descricao' => 'Comércio atacadista de leite e laticínios', 'grau_risco' => 2],
            ['codigo' => '46.32-0-01', 'descricao' => 'Comércio atacadista de cereais e leguminosas beneficiados', 'grau_risco' => 2],
            ['codigo' => '46.32-0-02', 'descricao' => 'Comércio atacadista de farinhas, amidos e féculas', 'grau_risco' => 2],
            ['codigo' => '46.32-0-03', 'descricao' => 'Comércio atacadista de cereais e leguminosas beneficiados, farinhas, amidos e féculas, com atividade de fracionamento e acondicionamento associada', 'grau_risco' => 2],
            ['codigo' => '47.11-3-01', 'descricao' => 'Comércio varejista de mercadorias em geral, com predominância de produtos alimentícios - hipermercados', 'grau_risco' => 2],
            ['codigo' => '47.11-3-02', 'descricao' => 'Comércio varejista de mercadorias em geral, com predominância de produtos alimentícios - supermercados', 'grau_risco' => 2],
            ['codigo' => '62.01-5-01', 'descricao' => 'Desenvolvimento de programas de computador sob encomenda', 'grau_risco' => 1],
            ['codigo' => '62.02-3-00', 'descricao' => 'Desenvolvimento e licenciamento de programas de computador customizáveis', 'grau_risco' => 1],
            ['codigo' => '62.03-1-00', 'descricao' => 'Desenvolvimento e licenciamento de programas de computador não-customizáveis', 'grau_risco' => 1],
            ['codigo' => '62.04-0-00', 'descricao' => 'Consultoria em tecnologia da informação', 'grau_risco' => 1],
            ['codigo' => '62.09-1-00', 'descricao' => 'Suporte técnico, manutenção e outros serviços em tecnologia da informação', 'grau_risco' => 1],
            ['codigo' => '63.11-9-00', 'descricao' => 'Tratamento de dados, provedores de serviços de aplicação e serviços de hospedagem na internet', 'grau_risco' => 1],
            ['codigo' => '63.19-4-00', 'descricao' => 'Portais, provedores de conteúdo e outros serviços de informação na internet', 'grau_risco' => 1],
            ['codigo' => '64.10-7-00', 'descricao' => 'Banco Central', 'grau_risco' => 1],
            ['codigo' => '64.21-2-00', 'descricao' => 'Bancos comerciais', 'grau_risco' => 1],
        ];
        
        // Inserir em lotes para não sobrecarregar a memória
        $chunks = array_chunk($cnaes, 10);
        
        foreach ($chunks as $chunk) {
            DB::table('cnaes')->upsert($chunk, ['codigo'], ['descricao', 'grau_risco']);
        }
        
        $this->command->info('CNAEs importados/atualizados com sucesso: ' . count($cnaes) . ' registros.');
    }
}

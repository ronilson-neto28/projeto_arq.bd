<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ExameSeeder extends Seeder
{
    public function run(): void
    {
        $allowed = Schema::getColumnListing('exames');

        $exames = [
            ['nome'=>'Exame Clínico Ocupacional Admissional','tipo'=>'admissional','observacoes'=>'Exame médico obrigatório para admissão de funcionários'],
            ['nome'=>'Exame Audiométrico Admissional','tipo'=>'admissional','observacoes'=>'Avaliação da capacidade auditiva para funções com exposição a ruído'],
            ['nome'=>'Exame Clínico Ocupacional Periódico','tipo'=>'periodico','observacoes'=>'Exame médico periódico conforme PCMSO'],
            ['nome'=>'Exame Audiométrico Periódico','tipo'=>'periodico','observacoes'=>'Controle audiométrico periódico para trabalhadores expostos a ruído'],
            ['nome'=>'Exame Oftalmológico Periódico','tipo'=>'periodico','observacoes'=>'Avaliação da acuidade visual e saúde ocular'],
            ['nome'=>'Exame Clínico Ocupacional Demissional','tipo'=>'demissional','observacoes'=>'Exame médico obrigatório para desligamento de funcionários'],
            ['nome'=>'Exame Audiométrico Demissional','tipo'=>'demissional','observacoes'=>'Avaliação audiométrica final para trabalhadores expostos a ruído'],
            ['nome'=>'Exame de Retorno ao Trabalho','tipo'=>'retorno','observacoes'=>'Exame para retorno após afastamento superior a 30 dias'],
            ['nome'=>'Exame de Mudança de Função - Clínico','tipo'=>'mudanca_funcao','observacoes'=>'Avaliação médica para mudança de função ou setor'],
            ['nome'=>'Exame de Mudança de Função - Audiométrico','tipo'=>'mudanca_funcao','observacoes'=>'Audiometria para mudança de função com alteração de exposição a ruído'],
        ];

        foreach ($exames as $e) {
            $row = array_intersect_key(
                array_merge($e, ['created_at'=>now(),'updated_at'=>now()]),
                array_flip($allowed)
            );

            DB::table('exames')->updateOrInsert(
                ['nome' => $e['nome']],
                array_diff_key($row, array_flip(['nome','created_at']))
            );
        }

        $this->command?->info('Exames importados/atualizados com sucesso (sem índice único).');
    }
}

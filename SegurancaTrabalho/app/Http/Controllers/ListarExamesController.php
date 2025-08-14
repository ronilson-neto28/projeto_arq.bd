<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Funcionario;

class ListarExamesController extends Controller
{
    public function index()
    {
        // Lê do banco (apenas empresas e funcionários)
        $empresas = Empresa::select('id','nome')->orderBy('nome')->get();
        $funcionarios = Funcionario::select('id','nome','empresa_id')->orderBy('nome')->get();

        // MOCK: exames apenas para visual
        $exames = collect([
            [
                'id' => 101,
                'empresa_id' => optional($empresas->first())->id,
                'empresa' => optional($empresas->first())->nome ?? 'Empresa Exemplo',
                'funcionario_id' => optional($funcionarios->first())->id,
                'funcionario' => optional($funcionarios->first())->nome ?? 'Fulano da Silva',
                'tipo' => 'periodico',
                'titulo' => 'ASO Periódico',
                'status' => 'pendente',
                'data_solicitacao' => '2025-08-01',
                'data_agendamento' => null,
                'data_realizacao'  => null,
                'validade_ate'     => '2026-08-01',
                'observacoes'      => 'Gerado a partir dos riscos do cargo.',
            ],
            [
                'id' => 102,
                'empresa_id' => optional($empresas->get(1))->id ?? optional($empresas->first())->id,
                'empresa' => $empresas->get(1)->nome ?? (optional($empresas->first())->nome ?? 'Empresa Exemplo'),
                'funcionario_id' => optional($funcionarios->get(1))->id ?? optional($funcionarios->first())->id,
                'funcionario' => $funcionarios->get(1)->nome ?? (optional($funcionarios->first())->nome ?? 'Ciclano Souza'),
                'tipo' => 'admissional',
                'titulo' => 'ASO Admissional',
                'status' => 'agendado',
                'data_solicitacao' => '2025-07-28',
                'data_agendamento' => '2025-08-15',
                'data_realizacao'  => null,
                'validade_ate'     => '2026-08-15',
                'observacoes'      => 'Documentação enviada ao prestador.',
            ],
            [
                'id' => 103,
                'empresa_id' => optional($empresas->get(2))->id ?? optional($empresas->first())->id,
                'empresa' => $empresas->get(2)->nome ?? (optional($empresas->first())->nome ?? 'Empresa Exemplo'),
                'funcionario_id' => optional($funcionarios->get(2))->id ?? optional($funcionarios->first())->id,
                'funcionario' => $funcionarios->get(2)->nome ?? (optional($funcionarios->first())->nome ?? 'Beltrano Lima'),
                'tipo' => 'retorno',
                'titulo' => 'ASO Retorno ao Trabalho',
                'status' => 'realizado',
                'data_solicitacao' => '2025-07-10',
                'data_agendamento' => '2025-07-12',
                'data_realizacao'  => '2025-07-12',
                'validade_ate'     => '2026-07-12',
                'observacoes'      => 'Apto sem restrições.',
            ],
            [
                'id' => 104,
                'empresa_id' => optional($empresas->first())->id,
                'empresa' => optional($empresas->first())->nome ?? 'Empresa Exemplo',
                'funcionario_id' => optional($funcionarios->get(3))->id ?? optional($funcionarios->first())->id,
                'funcionario' => $funcionarios->get(3)->nome ?? (optional($funcionarios->first())->nome ?? 'Maria Santos'),
                'tipo' => 'periodico',
                'titulo' => 'ASO Periódico',
                'status' => 'vencido',
                'data_solicitacao' => '2024-06-05',
                'data_agendamento' => '2024-06-20',
                'data_realizacao'  => '2024-06-20',
                'validade_ate'     => '2025-06-20',
                'observacoes'      => 'Necessário renovação.',
            ],
        ]);

        return view('pages.forms.listar-exames', compact('empresas','funcionarios','exames'));
    }
}

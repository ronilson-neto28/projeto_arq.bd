<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Funcionario;
use App\Models\Encaminhamento;

class ListarExamesController extends Controller
{
    public function index()
    {
        // Lê do banco (apenas empresas e funcionários)
        $empresas = Empresa::select('id','razao_social as nome')->orderBy('razao_social')->get();
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

    public function imprimir($id)
    {
        // Busca o encaminhamento real do banco ou usa mock se não existir
        $encaminhamento = Encaminhamento::with(['funcionario', 'empresa', 'cargo'])->find($id);
        
        if (!$encaminhamento) {
            // Se não encontrar no banco, usa dados mock baseado no ID
            $empresas = Empresa::select('id','razao_social as nome','cnpj')->orderBy('razao_social')->get();
            $funcionarios = Funcionario::select('id','nome','cpf','rg','data_nascimento','empresa_id','cargo_id')
                ->with('cargo')
                ->orderBy('nome')->get();
            
            // Mapeia os dados mock dos exames
            $exameMock = collect([
                [
                    'id' => 101,
                    'funcionario_id' => optional($funcionarios->first())->id,
                    'empresa_id' => optional($empresas->first())->id,
                    'tipo' => 'periodico',
                ],
                [
                    'id' => 102,
                    'funcionario_id' => optional($funcionarios->get(1))->id ?? optional($funcionarios->first())->id,
                    'empresa_id' => optional($empresas->get(1))->id ?? optional($empresas->first())->id,
                    'tipo' => 'admissional',
                ],
                [
                    'id' => 103,
                    'funcionario_id' => optional($funcionarios->get(2))->id ?? optional($funcionarios->first())->id,
                    'empresa_id' => optional($empresas->get(2))->id ?? optional($empresas->first())->id,
                    'tipo' => 'retorno',
                ],
                [
                    'id' => 104,
                    'funcionario_id' => optional($funcionarios->get(3))->id ?? optional($funcionarios->first())->id,
                    'empresa_id' => optional($empresas->first())->id,
                    'tipo' => 'periodico',
                ],
            ])->firstWhere('id', $id);
            
            if ($exameMock) {
                $funcionario = $funcionarios->firstWhere('id', $exameMock['funcionario_id']);
                $empresa = $empresas->firstWhere('id', $exameMock['empresa_id']);
                
                $encaminhamento = (object) [
                    'id' => $id,
                    'numero_guia' => 'EX-' . str_pad($id, 4, '0', STR_PAD_LEFT),
                    'data_emissao' => now()->format('d/m/Y'),
                    'funcionario' => (object) [
                         'nome' => strtoupper($funcionario->nome ?? 'FUNCIONÁRIO NÃO ENCONTRADO'),
                         'cpf' => $this->formatCpf($funcionario->cpf ?? '00000000000'),
                         'rg' => $funcionario->rg ?? '0000000-XX',
                         'data_nascimento' => $funcionario->data_nascimento ? \Carbon\Carbon::parse($funcionario->data_nascimento)->format('d/m/Y') : '01/01/1990',
                         'cargo' => (object) ['nome' => strtoupper($funcionario->cargo->nome ?? 'CARGO NÃO DEFINIDO')]
                     ],
                     'empresa' => (object) [
                         'razao_social' => strtoupper($empresa->nome ?? 'EMPRESA NÃO ENCONTRADA'),
                         'cnpj' => $this->formatCnpj($empresa->cnpj ?? '00000000000000')
                     ],
                    'tipo_exame' => strtoupper($exameMock['tipo']),
                    'data_atendimento' => now()->addDays(7)->format('d/m/Y'),
                    'observacoes' => 'Encaminhamento gerado automaticamente conforme riscos ocupacionais identificados.'
                ];
            } else {
                // Fallback se o ID não for encontrado
                $encaminhamento = (object) [
                    'id' => $id,
                    'numero_guia' => 'EX-' . str_pad($id, 4, '0', STR_PAD_LEFT),
                    'data_emissao' => now()->format('d/m/Y'),
                    'funcionario' => (object) [
                        'nome' => 'EXAME NÃO ENCONTRADO',
                        'cpf' => '000.000.000-00',
                        'rg' => '0000000-XX',
                        'data_nascimento' => '01/01/1990',
                        'cargo' => (object) ['nome' => 'CARGO NÃO DEFINIDO']
                    ],
                    'empresa' => (object) [
                        'razao_social' => 'EMPRESA NÃO ENCONTRADA',
                        'cnpj' => '00.000.000/0000-00'
                    ],
                    'tipo_exame' => 'NÃO DEFINIDO',
                    'data_atendimento' => now()->addDays(7)->format('d/m/Y'),
                    'observacoes' => 'Exame não encontrado no sistema.'
                ];
            }
        }
        
        return view('pages.forms.imprimir-encaminhamento', compact('encaminhamento'));
    }
    
    private function formatCpf($cpf)
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se tem 11 dígitos
        if (strlen($cpf) == 11) {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        }
        
        return $cpf;
    }
    
    private function formatCnpj($cnpj)
    {
        // Remove caracteres não numéricos
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        // Verifica se tem 14 dígitos
        if (strlen($cnpj) == 14) {
            return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
        }
        
        return $cnpj;
    }
}

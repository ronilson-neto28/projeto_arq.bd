<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Funcionario;
use App\Models\Encaminhamento;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmpresas = Empresa::count();
        $totalFuncionarios = Funcionario::count();
        $funcionarios = Funcionario::with('empresa')->get(); // carrega os dados com empresa associada
        
        // Dados para o gráfico de pizza dos encaminhamentos por tipo
        $examesPorTipo = Encaminhamento::select('tipo_exame', DB::raw('count(*) as total'))
            ->groupBy('tipo_exame')
            ->orderBy('total', 'desc')
            ->get();

        // Se não houver encaminhamentos, exibe dados padrão para o gráfico
        if ($examesPorTipo->isEmpty()) {
            $examesPorTipo = collect([
                (object)['tipo_exame' => 'Admissional', 'total' => 0],
                (object)['tipo_exame' => 'Demissional', 'total' => 0],
                (object)['tipo_exame' => 'Retorno', 'total' => 0],
                (object)['tipo_exame' => 'Periodico', 'total' => 0],
                (object)['tipo_exame' => 'Mudança de Função', 'total' => 0]
            ]);
        }
        
        // Dados para o gráfico de exames mensais (por data de atendimento)
        $examesPorMes = [];
        $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        
        for ($i = 1; $i <= 12; $i++) {
            $totalMes = Encaminhamento::whereRaw('EXTRACT(MONTH FROM data_atendimento) = ?', [$i])
                ->whereRaw('EXTRACT(YEAR FROM data_atendimento) = ?', [date('Y')])
                ->whereNotNull('data_atendimento')
                ->count();
            $examesPorMes[] = [
                'mes' => $meses[$i-1],
                'total' => $totalMes
            ];
        }
        
        // Dados para o gráfico de encaminhamentos por mês com filtro por ano
        $encaminhamentosPorMes = [];
        $anosDisponiveis = [];
        $mesesAbrev = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        
        // Buscar anos disponíveis nos encaminhamentos (usando data_atendimento)
        $anosComEncaminhamentos = Encaminhamento::selectRaw('EXTRACT(YEAR FROM data_atendimento) as ano')
            ->whereNotNull('data_atendimento')
            ->distinct()
            ->orderBy('ano', 'desc')
            ->pluck('ano')
            ->toArray();
        
        // Se não houver dados, incluir o ano atual
        if (empty($anosComEncaminhamentos)) {
            $anosComEncaminhamentos = [date('Y')];
        }
        
        $anosDisponiveis = $anosComEncaminhamentos;
        
        // Gerar dados para cada ano disponível
        foreach ($anosDisponiveis as $ano) {
            $dadosAno = [];
            for ($i = 1; $i <= 12; $i++) {
                $totalMes = Encaminhamento::whereRaw('EXTRACT(MONTH FROM data_atendimento) = ?', [$i])
                    ->whereRaw('EXTRACT(YEAR FROM data_atendimento) = ?', [$ano])
                    ->whereNotNull('data_atendimento')
                    ->count();
                $dadosAno[$mesesAbrev[$i-1]] = $totalMes;
            }
            $encaminhamentosPorMes[$ano] = $dadosAno;
        }
        
        $totalEncaminhamentos = Encaminhamento::count();

        return view('dashboard', compact('totalEmpresas', 'totalFuncionarios', 'funcionarios', 'examesPorTipo', 'totalEncaminhamentos', 'examesPorMes', 'encaminhamentosPorMes', 'anosDisponiveis'));
    }
}
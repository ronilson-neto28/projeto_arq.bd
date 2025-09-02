<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Funcionario;
use App\Models\Exame;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmpresas = Empresa::count();
        $totalFuncionarios = Funcionario::count();
        $funcionarios = Funcionario::with('empresa')->get(); // carrega os dados com empresa associada
        
        // Dados para o gráfico de pizza dos exames
        // Como a tabela exames não tem coluna 'tipo', vamos usar dados mock temporários
        $examesPorTipo = collect([
            (object)['tipo_exame' => 'admissional', 'total' => 15],
            (object)['tipo_exame' => 'periodico', 'total' => 45],
            (object)['tipo_exame' => 'demissional', 'total' => 8],
            (object)['tipo_exame' => 'retorno', 'total' => 12],
            (object)['tipo_exame' => 'mudanca_funcao', 'total' => 5]
        ]);
        
        $totalExames = Exame::count();

        return view('dashboard', compact('totalEmpresas', 'totalFuncionarios', 'funcionarios', 'examesPorTipo', 'totalExames'));
    }
}
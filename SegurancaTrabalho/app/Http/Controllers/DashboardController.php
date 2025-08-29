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
        $examesPorTipo = Exame::select('tipo', DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->get();
        
        $totalExames = Exame::count();

        return view('dashboard', compact('totalEmpresas', 'totalFuncionarios', 'funcionarios', 'examesPorTipo', 'totalExames'));
    }
}
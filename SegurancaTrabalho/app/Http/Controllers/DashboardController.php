<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Funcionario; 

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmpresas = Empresa::count();
        $totalFuncionarios = Funcionario::count();
        $funcionarios = Funcionario::with('empresa')->get(); // carrega os dados com empresa associada


        return view('dashboard', compact('totalEmpresas', 'totalFuncionarios', 'funcionarios'));
    }
}
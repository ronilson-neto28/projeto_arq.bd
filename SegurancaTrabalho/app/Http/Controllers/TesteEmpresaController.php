<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class TesteEmpresaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas,cnpj',
        ]);

        Empresa::create($request->only(['nome', 'cnpj']));

        return redirect()->route('empresa.testar')->with('success', 'Empresa salva com sucesso!');
    }
}

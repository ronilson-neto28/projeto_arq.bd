<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Empresa;
use App\Models\Cargo;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $funcionarios = Funcionario::with(['empresa', 'cargo'])->orderBy('created_at', 'desc')->get();
        return view('pages.forms.listar-funcionario', compact('funcionarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresas = Empresa::orderBy('nome')->get();
        $cargos = Cargo::orderBy('nome')->get();

        return view('pages.forms.cadastrar-funcionario', compact('empresas', 'cargos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios',
            'genero' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'empresa_id' => 'required|exists:empresas,id',
            'cargo_id' => 'required|exists:cargos,id',
        ]);

        Funcionario::create($request->only([
            'nome',
            'email',
            'genero',
            'data_nascimento',
            'empresa_id',
            'cargo_id',
        ]));

        return redirect()->route('funcionarios.create')->with('success', 'Funcionário cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Funcionario $funcionario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Funcionario $funcionario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Funcionario $funcionario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Funcionario $funcionario)
    {
        //
    }
}

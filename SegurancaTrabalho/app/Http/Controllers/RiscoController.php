<?php

namespace App\Http\Controllers;

use App\Models\TipoDeRisco;
use App\Models\Risco;
use Illuminate\Http\Request;

class RiscoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoDeRisco::orderBy('nome')->get();
        return view('pages.forms.cadastrar-risco', compact('tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo_de_risco_id' => 'required|exists:tipos_de_risco,id',
            'descricao' => 'nullable|string',
        ]);

        Risco::create([
            'nome' => $request->nome,
            'tipo_de_risco_id' => $request->tipo_de_risco_id,
            'descricao' => $request->descricao,
        ]);

        return redirect()->route('riscos.create')->with('success', 'Risco cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Risco $risco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Risco $risco)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Risco $risco)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Risco $risco)
    {
        //
    }
}

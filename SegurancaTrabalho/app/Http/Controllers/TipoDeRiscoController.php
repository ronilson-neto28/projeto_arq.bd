<?php

namespace App\Http\Controllers;

use App\Models\TipoDeRisco;
use Illuminate\Http\Request;

class TipoDeRiscoController extends Controller
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
        return view('pages.forms.cadastrar-tipo-risco'); // você envia depois
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:tipos_de_risco,nome',
        ]);

        TipoDeRisco::create([
            'nome' => $request->nome,
        ]);

        return redirect()->route('tipos_risco.create')->with('success', 'Tipo de risco cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoDeRisco $tipoDeRisco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoDeRisco $tipoDeRisco)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoDeRisco $tipoDeRisco)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoDeRisco $tipoDeRisco)
    {
        //
    }
}

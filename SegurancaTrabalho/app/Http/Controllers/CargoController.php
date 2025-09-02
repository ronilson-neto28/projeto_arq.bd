<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CargoController extends Controller
{
    // GET /cargos/listar
    public function index(Request $request)
    {
        $busca = trim((string) $request->get('search', ''));
        $empresaId = $request->get('empresa_id');

        $query = Cargo::query()
            ->with('empresa')
            ->when($empresaId, fn($q) => $q->where('empresa_id', $empresaId))
            ->when($busca !== '', function ($q) use ($busca) {
                $driver = DB::getDriverName();
                $likeOp = $driver === 'pgsql' ? 'ilike' : 'like';
                $q->where('nome', $likeOp, "%{$busca}%");
            })
            ->orderBy('empresa_id')
            ->orderBy('nome');

        $cargos = $query->paginate(15)->appends($request->only('search','empresa_id'));

        // para filtro por empresa no select
        $empresas = DB::table('empresas')->select('id','razao_social','nome_fantasia')->orderBy('razao_social')->get();

        return view('pages.forms.listar-cargo', compact('cargos', 'empresas', 'busca', 'empresaId'));
    }

    // GET /cargos/cadastrar
    public function create()
    {
        $empresas = DB::table('empresas')->select('id','razao_social','nome_fantasia')->orderBy('razao_social')->get();
        return view('pages.forms.cadastrar-cargo', compact('empresas'));
    }

    // POST /cargos/cadastrar
    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id' => ['required', 'exists:empresas,id'],
            'nome'       => [
                'required','string','max:255',
                // unicidade composta (empresa_id + nome)
                Rule::unique('cargos')->where(fn($q) => $q->where('empresa_id', $request->input('empresa_id'))),
            ],
        ]);

        Cargo::create($data);

        return redirect()
            ->route('cargos.index')
            ->with('success', 'Cargo cadastrado com sucesso!');
    }
}

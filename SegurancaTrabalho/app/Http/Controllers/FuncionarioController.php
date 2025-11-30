<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Empresa;
use App\Models\Cargo;
use MongoDB\BSON\ObjectId;
use Carbon\Carbon;

class FuncionarioController extends Controller
{
    public function create()
    {
        $empresas = Empresa::all();
        $cargos = Cargo::query()->orderBy('nome')->get();
        return view('pages.forms.cadastrar-funcionario', compact('empresas','cargos'));
    }

    public function index()
    {
        return view('pages.forms.listar-funcionario');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|min:11|max:14|unique:funcionarios,cpf',
            'rg' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date_format:d/m/Y',
            'genero' => 'nullable|string|in:M,F,O',
            'estado_civil' => 'nullable|string',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'empresa_id' => 'required|string',
            'cargo_id' => 'nullable|string',
            'data_admissao' => 'nullable|date_format:d/m/Y',
            'setor' => 'nullable|string|max:100',
            'turno' => 'nullable|string',
        ]);

        // normalizações
        $data['cpf'] = preg_replace('/\D+/', '', (string)($data['cpf'] ?? ''));
        if (!empty($data['empresa_id'])) {
            $data['empresa_id'] = new ObjectId($data['empresa_id']);
        }
        if (!empty($data['cargo_id'])) {
            $data['cargo_id'] = new ObjectId($data['cargo_id']);
        }

        if (!empty($data['data_nascimento'])) {
            $data['data_nascimento'] = Carbon::createFromFormat('d/m/Y', $data['data_nascimento']);
        }
        if (!empty($data['data_admissao'])) {
            $data['data_admissao'] = Carbon::createFromFormat('d/m/Y', $data['data_admissao']);
        }

        try {
            // valida empresa existente
            if (!Empresa::query()->where('_id', $data['empresa_id'])->exists()) {
                return redirect()->back()->withInput()->withErrors(['empresa_id' => 'Empresa inválida']);
            }
            Funcionario::create($data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao salvar o funcionário. Por favor, tente novamente.']);
        }

        return redirect()->route('funcionarios.index')->with('success', 'Funcionário cadastrado com sucesso!');
    }
}

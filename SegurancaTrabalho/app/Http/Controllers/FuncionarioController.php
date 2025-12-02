<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Empresa;
use App\Models\Cargo;
use Carbon\Carbon;
use MongoDB\BSON\ObjectId;

class FuncionarioController extends Controller
{
    public function create()
    {
        $empresas = Empresa::all();
        $cargos = Cargo::query()->orderBy('nome')->get();
        return view('pages.forms.cadastrar-funcionario', compact('empresas','cargos'));
    }

    public function index(Request $request)
    {
        $query = Funcionario::with(['empresa','cargo']);

        $busca = $request->input('buscaFunc');
        $empresaId = $request->input('filtroEmpresaFunc');
        $cargoId = $request->input('filtroCargoFunc');

        if ($empresaId) {
            $query->where('empresa_id', $empresaId);
        }

        if ($cargoId) {
            $query->where('cargo_id', $cargoId);
        }

        if ($busca) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('cpf', 'like', "%{$busca}%")
                  ->orWhere('email', 'like', "%{$busca}%");
            });
        }

        $funcionarios = $query->orderBy('nome', 'asc')->paginate(15);

        $empresas = Empresa::all(['_id','razao_social','nome_fantasia']);
        $cargos = Cargo::all(['_id','nome','empresa_id']);

        return view('pages.forms.listar-funcionario', [
            'funcionarios' => $funcionarios,
            'empresas' => $empresas,
            'cargos' => $cargos,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|min:11|max:14',
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
            $data['empresa_id'] = new \MongoDB\BSON\ObjectId($data['empresa_id']);
        }
        if (!empty($data['cargo_id'])) {
            $data['cargo_id'] = new \MongoDB\BSON\ObjectId($data['cargo_id']);
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

    public function edit($id)
    {
        $funcionario = Funcionario::find($id);
        if (!$funcionario && preg_match('/^[0-9a-f]{24}$/i', (string) $id)) {
            $funcionario = Funcionario::query()->where('_id', new \MongoDB\BSON\ObjectId($id))->first();
        }
        if (!$funcionario) {
            return redirect()->route('funcionarios.index')->with('error', 'Funcionário não encontrado.');
        }

        $empresas = Empresa::all(['_id', 'razao_social', 'nome_fantasia']);
        $cargos = Cargo::all(['_id', 'nome', 'empresa_id']);

        return view('pages.forms.editar-funcionario', compact('funcionario', 'empresas', 'cargos'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|min:11|max:14',
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

        $funcionario = Funcionario::find($id);
        if (!$funcionario && preg_match('/^[0-9a-f]{24}$/i', (string) $id)) {
            $funcionario = Funcionario::query()->where('_id', new ObjectId($id))->first();
        }
        if (!$funcionario) {
            return redirect()->route('funcionarios.index')->with('error', 'Funcionário não encontrado.');
        }

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

        $telefoneSimples = $data['telefone'] ?? null;
        unset($data['telefone']);

        try {
            $funcionario->update($data);
            // atualizar telefones subdocumento
            $funcionario->telefones()->delete();
            if ($telefoneSimples) {
                $numero = preg_replace('/\D+/', '', (string)$telefoneSimples);
                $funcionario->telefones()->create([
                    'numero' => $numero ?: $telefoneSimples,
                    'tipo' => 'Celular',
                    'contato' => 'Pessoal',
                ]);
            }
            return redirect()->route('funcionarios.index')->with('success', 'Funcionário atualizado com sucesso!');
        } catch (\Throwable $e) {
            \Log::error('Erro ao atualizar funcionário: '.$e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao atualizar funcionário.']);
        }
    }

    public function destroy($id)
    {
        $funcionario = Funcionario::find($id);
        if (!$funcionario && preg_match('/^[0-9a-f]{24}$/i', (string) $id)) {
            $funcionario = Funcionario::query()->where('_id', new \MongoDB\BSON\ObjectId($id))->first();
        }
        if (!$funcionario) {
            return redirect()->route('funcionarios.index')->with('error', 'Funcionário não encontrado.');
        }
        try {
            $funcionario->delete();
            return redirect()->route('funcionarios.index')->with('success', 'Funcionário excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('funcionarios.index')->with('error', 'Erro ao excluir funcionário.');
        }
    }
}

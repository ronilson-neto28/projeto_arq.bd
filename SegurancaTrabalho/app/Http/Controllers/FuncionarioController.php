<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class FuncionarioController extends Controller
{
    // GET /funcionarios/listar
    public function index(Request $request)
    {
        $busca = trim((string) $request->get('search', ''));
        $empresaId = $request->get('empresa_id');
        $cargoId   = $request->get('cargo_id');

        $driver = DB::getDriverName();
        $likeOp = $driver === 'pgsql' ? 'ilike' : 'like';

        $query = Funcionario::query()
            ->with(['empresa','cargo'])
            ->when($empresaId, fn($q) => $q->where('empresa_id', $empresaId))
            ->when($cargoId,   fn($q) => $q->where('cargo_id', $cargoId))
            ->when($busca !== '', function ($q) use ($busca, $likeOp) {
                $q->where(function ($w) use ($busca, $likeOp) {
                    $w->where('nome', $likeOp, "%{$busca}%")
                      ->orWhere('cpf', 'like', "%{$busca}%")
                      ->orWhere('rg',  $likeOp, "%{$busca}%")
                      ->orWhere('email', $likeOp, "%{$busca}%");
                });
            })
            ->orderBy('nome');

        $funcionarios = $query->paginate(15)->appends($request->only('search','empresa_id','cargo_id'));

        $empresas = DB::table('empresas')->select('id','razao_social','nome_fantasia')->orderBy('razao_social')->get();
        $cargos   = DB::table('cargos')->select('id','empresa_id','nome')->orderBy('empresa_id')->orderBy('nome')->get();

        // ajuste o caminho da view conforme seu projeto
        return view('pages.forms.listar-funcionario', compact('funcionarios','empresas','cargos','busca','empresaId','cargoId'));
    }

    // GET /funcionarios/cadastrar
    public function create()
    {
        $empresas = DB::table('empresas')->select('id','razao_social','nome_fantasia')->orderBy('razao_social')->get();
        // por padrão, traga todos cargos; na view você pode filtrar por empresa via JS
        $cargos   = DB::table('cargos')->select('id','empresa_id','nome')->orderBy('empresa_id')->orderBy('nome')->get();

        return view('pages.forms.cadastrar-funcionario', compact('empresas','cargos'));
    }

    // POST /funcionarios/cadastrar
    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id'      => ['required','exists:empresas,id'],
            'cargo_id'        => ['required','exists:cargos,id'],

            'nome'            => ['required','string','max:255'],
            'cpf'             => ['required','regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$|^\d{11}$/','unique:funcionarios,cpf'],
            'rg'              => ['nullable','string','max:50'],
            'data_nascimento' => ['nullable','date_format:d/m/Y'],
            'genero'          => ['nullable','string','max:30'],
            'estado_civil'    => ['nullable','string','max:30'],
            'email'           => ['nullable','email','max:255'],
            'data_admissao'   => ['nullable','date_format:d/m/Y'],

            'setor_id'        => ['nullable','integer'], // quando criarmos setores, podemos trocar para exists:setores,id
        ]);

        // normaliza CPF antes de salvar
        $data['cpf'] = preg_replace('/\D+/', '', $data['cpf']);

        // converte gênero de sigla para texto completo
        if (!empty($data['genero'])) {
            $generoMap = [
                'M' => 'Masculino',
                'F' => 'Feminino',
                'O' => 'Outro'
            ];
            $data['genero'] = $generoMap[$data['genero']] ?? $data['genero'];
        }

        // converte datas do formato brasileiro para formato do banco
        if (!empty($data['data_nascimento'])) {
            $data['data_nascimento'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['data_nascimento'])->format('Y-m-d');
        }
        if (!empty($data['data_admissao'])) {
            $data['data_admissao'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['data_admissao'])->format('Y-m-d');
        }

        // (opcional) valida se o cargo pertence à empresa selecionada
        $cargoEmpresaId = DB::table('cargos')->where('id', $data['cargo_id'])->value('empresa_id');
        if ($cargoEmpresaId && (int)$cargoEmpresaId !== (int)$data['empresa_id']) {
            return back()
                ->withErrors(['cargo_id' => 'O cargo selecionado não pertence à empresa informada.'])
                ->withInput();
        }

        Funcionario::create($data);

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionário cadastrado com sucesso!');
    }

    // GET /funcionarios/{id}/editar
    public function edit($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $empresas = DB::table('empresas')->select('id','razao_social','nome_fantasia')->orderBy('razao_social')->get();
        $cargos = DB::table('cargos')->select('id','empresa_id','nome')->orderBy('empresa_id')->orderBy('nome')->get();

        return view('pages.forms.editar-funcionario', compact('funcionario','empresas','cargos'));
    }

    // PUT /funcionarios/{id}
    public function update(Request $request, $id)
    {
        $funcionario = Funcionario::findOrFail($id);

        $data = $request->validate([
            'empresa_id'      => ['required','exists:empresas,id'],
            'cargo_id'        => ['required','exists:cargos,id'],

            'nome'            => ['required','string','max:255'],
            'cpf'             => ['required','regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$|^\d{11}$/','unique:funcionarios,cpf,'.$id],
            'rg'              => ['nullable','string','max:50'],
            'data_nascimento' => ['nullable','date_format:d/m/Y'],
            'genero'          => ['nullable','string','max:30'],
            'estado_civil'    => ['nullable','string','max:30'],
            'email'           => ['nullable','email','max:255'],
            'data_admissao'   => ['nullable','date_format:d/m/Y'],

            'setor_id'        => ['nullable','integer'],
        ]);

        // normaliza CPF antes de salvar
        $data['cpf'] = preg_replace('/\D+/', '', $data['cpf']);

        // converte gênero de sigla para texto completo
        if (!empty($data['genero'])) {
            $generoMap = [
                'M' => 'Masculino',
                'F' => 'Feminino',
                'O' => 'Outro'
            ];
            $data['genero'] = $generoMap[$data['genero']] ?? $data['genero'];
        }

        // converte datas do formato brasileiro para formato do banco
        if (!empty($data['data_nascimento'])) {
            $data['data_nascimento'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['data_nascimento'])->format('Y-m-d');
        }
        if (!empty($data['data_admissao'])) {
            $data['data_admissao'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['data_admissao'])->format('Y-m-d');
        }

        // valida se o cargo pertence à empresa selecionada
        $cargoEmpresaId = DB::table('cargos')->where('id', $data['cargo_id'])->value('empresa_id');
        if ($cargoEmpresaId && (int)$cargoEmpresaId !== (int)$data['empresa_id']) {
            return back()
                ->withErrors(['cargo_id' => 'O cargo selecionado não pertence à empresa informada.'])
                ->withInput();
        }

        $funcionario->update($data);

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionário atualizado com sucesso!');
    }

    // DELETE /funcionarios/{id}
    public function destroy($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->delete();

        return redirect()
            ->route('funcionarios.index')
            ->with('success', 'Funcionário excluído com sucesso!');
    }
}

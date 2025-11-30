<?php

namespace App\Services\Mongo;

use App\Models\Empresa;
use MongoDB\BSON\ObjectId;

class EmpresaMongoService
{
    public function listarTodos()
    {
        return Empresa::query()->orderBy('razao_social')->get();
    }

    public function buscarPorId(string $id)
    {
        $key = preg_match('/^[0-9a-f]{24}$/i', $id) ? '_id' : 'id';
        return Empresa::query()->where($key, $key === '_id' ? new ObjectId($id) : $id)->first();
    }

    public function criar(array $dados)
    {
        return Empresa::create($dados);
    }

    public function atualizar(string $id, array $dados)
    {
        $empresa = $this->buscarPorId($id);
        if (!$empresa) {
            return null;
        }
        $empresa->fill($dados);
        $empresa->save();
        return $empresa;
    }

    public function deletar(string $id)
    {
        $empresa = $this->buscarPorId($id);
        if (!$empresa) {
            return false;
        }
        return (bool) $empresa->delete();
    }
}

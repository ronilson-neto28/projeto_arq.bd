<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Subdocument\Telefone;
use App\Models\Cnae;
use App\Models\Funcionario;

class Empresa extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'empresas';
    protected $primaryKey = '_id';

    protected $fillable = [
        'razao_social',
        'cnpj',
        'nome_fantasia',
        'cnae_id',
        'grau_risco',
        'cep',
        'endereco',
        'bairro',
        'cidade',
        'uf',
        'email',
    ];

    public function telefones()
    {
        return $this->embedsMany(Telefone::class, 'telefones');
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class, 'empresa_id');
    }

    public function cnae()
    {
        return $this->belongsTo(Cnae::class, 'cnae_id');
    }
}

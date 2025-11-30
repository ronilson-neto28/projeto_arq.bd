<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Subdocument\Telefone;

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
}

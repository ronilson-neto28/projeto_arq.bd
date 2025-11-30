<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Cnae extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cnaes';

    protected $fillable = [
        'codigo',
        'descricao',
        'grau_risco',
    ];

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'cnae_id');
    }
}


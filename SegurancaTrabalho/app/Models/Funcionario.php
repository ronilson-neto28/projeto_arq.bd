<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Funcionario extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'funcionarios';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nome',
        'cpf',
        'rg',
        'data_nascimento',
        'genero',
        'estado_civil',
        'telefone',
        'email',
        'empresa_id',
        'cargo_id',
        'data_admissao',
        'setor',
        'turno',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_admissao' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}

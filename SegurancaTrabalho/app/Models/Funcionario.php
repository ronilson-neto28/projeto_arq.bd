<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Subdocument\Telefone;
use App\Models\Cargo;

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

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function telefones()
    {
        return $this->embedsMany(Telefone::class, 'telefones');
    }
}

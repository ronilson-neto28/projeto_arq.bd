<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'genero',
        'data_nascimento',
        'empresa_id',
        'cargo_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function telefones()
    {
        return $this->hasMany(Telefone::class);
    }
}

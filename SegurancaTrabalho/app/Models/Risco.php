<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Risco extends Model
{
    protected $fillable = ['nome', 'tipo_de_risco_id', 'descricao'];

    public function tipo()
    {
        return $this->belongsTo(TipoDeRisco::class, 'tipo_de_risco_id');
    }

    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'cargo_risco')->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Risco extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',             // ex.: Ruído, Poeiras, Biológicos...
        'tipo_de_risco_id', // FK -> TipoDeRisco (Físico, Químico, ...)
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoDeRisco::class, 'tipo_de_risco_id');
    }

    public function cargos(): BelongsToMany
    {
        return $this->belongsToMany(Cargo::class, 'cargo_risco')
            ->withPivot(['fonte_geradora','intensidade','medidas_controle'])
            ->withTimestamps();
    }
}

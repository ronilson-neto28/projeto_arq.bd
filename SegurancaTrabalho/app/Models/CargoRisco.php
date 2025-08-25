<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class CargoRisco extends Model
{
    use HasFactory;

    /** Pivot explícito (útil p/ CRUD direto) */
    protected $table = 'cargo_risco';

    protected $fillable = [
        'cargo_id',
        'risco_id',
        'fonte_geradora',
        'intensidade',
        'medidas_controle',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function risco(): BelongsTo
    {
        return $this->belongsTo(Risco::class);
    }
}

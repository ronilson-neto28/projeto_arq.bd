<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDeRisco extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', // Físico | Químico | Biológico | Ergonômico | Acidente
    ];

    public function riscos(): HasMany
    {
        return $this->hasMany(Risco::class, 'tipo_de_risco_id');
    }
}

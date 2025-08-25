<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cnae extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'descricao',
        'grau_risco', // 1..4 (NR-4)
    ];

    protected $casts = [
        'grau_risco' => 'integer',
    ];

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class);
    }
}

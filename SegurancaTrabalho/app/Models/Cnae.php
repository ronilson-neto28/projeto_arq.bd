<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cnae extends Model
{
    protected $table = 'cnaes';

    protected $fillable = [
        'codigo',
        'descricao',
        'grau_risco',
    ];

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'cnae_id');
    }
}

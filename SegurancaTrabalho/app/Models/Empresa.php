<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'cnae_id',
        'grau_risco', // snapshot do CNAE
        'telefone',
        'email',
        'endereco',
        'cidade',
        'uf',
    ];

    protected $casts = [
        'grau_risco' => 'integer',
    ];

    public function cnae(): BelongsTo
    {
        return $this->belongsTo(Cnae::class);
    }

    public function cargos(): HasMany
    {
        return $this->hasMany(Cargo::class);
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class);
    }

    /** Se mantiver tabela de telefones */
    public function telefones(): HasMany
    {
        return $this->hasMany(Telefone::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'cnae_id',
        'grau_risco',
        'cep',
        'endereco',
        'bairro',
        'cidade',
        'uf',
        'email',
    ];

    // Normaliza CNPJ/CEP para só dígitos
    public function setCnpjAttribute($value): void
    {
        $this->attributes['cnpj'] = preg_replace('/\D+/', '', (string) $value);
    }
    public function setCepAttribute($value): void
    {
        $this->attributes['cep'] = $value ? preg_replace('/\D+/', '', (string) $value) : null;
    }

    // Relacionamentos
    public function cnae(): BelongsTo
    {
        return $this->belongsTo(Cnae::class, 'cnae_id');
    }

    public function cargos(): HasMany
    {
        return $this->hasMany(Cargo::class);
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class);
    }

    public function telefones(): HasMany
    {
        return $this->hasMany(Telefone::class);
    }
}

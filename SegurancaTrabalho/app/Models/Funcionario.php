<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Funcionario extends Model
{
    protected $table = 'funcionarios';

    protected $fillable = [
        'empresa_id',
        'cargo_id',
        'nome',
        'cpf',
        'rg',
        'data_nascimento',
        'genero',
        'estado_civil',
        'email',
        'data_admissao',
        'setor_id',
    ];

    // normaliza CPF (só dígitos)
    public function setCpfAttribute($value): void
    {
        $this->attributes['cpf'] = preg_replace('/\D+/', '', (string) $value);
    }

    // relacionamentos
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    // quando criarmos a tabela telefones
    public function telefones(): HasMany
    {
        return $this->hasMany(Telefone::class, 'funcionario_id');
    }
}

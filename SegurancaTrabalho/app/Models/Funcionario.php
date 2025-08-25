<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Funcionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'cargo_id',
        'nome',
        'cpf',
        'data_nascimento',
        'genero',     // M|F|O
        'telefone',
        'email',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    /** Se mantiver tabela de telefones */
    public function telefones(): HasMany
    {
        return $this->hasMany(Telefone::class);
    }
}

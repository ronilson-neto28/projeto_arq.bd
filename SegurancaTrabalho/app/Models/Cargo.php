<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'descricao',
        'nome',
    ];

    /** Empresa dona do cargo */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /** Funcionários que ocupam este cargo */
    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class);
    }

    /**
     * Riscos associados ao cargo (PGR)
     * Campos extras no pivot permitem registrar fonte/intensidade/medidas de controle
     */
    public function riscos(): BelongsToMany
    {
        return $this->belongsToMany(Risco::class, 'cargo_risco')
            ->withPivot(['fonte_geradora','intensidade','medidas_controle'])
            ->withTimestamps();
    }
}

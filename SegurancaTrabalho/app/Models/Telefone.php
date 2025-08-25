<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Telefone extends Model
{
    use HasFactory;

    // Somente as colunas que existem na tabela 'telefones'
    protected $fillable = [
        'funcionario_id',
        'numero', // salve apenas dígitos; formata na view
    ];

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }
}

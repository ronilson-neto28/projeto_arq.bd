<?php

namespace App\Models\Subdocument;

use MongoDB\Laravel\Eloquent\Model;

class EncaminhamentoItem extends Model
{
    protected $fillable = [
        'exame_id',
        'nome_snapshot',
        'nome_exame_snapshot',
        'periodicidade',
        'data',
        'hora',
        'prestador',
        'status',
        'laudo_path',
        'resultado',
        'justificativa',
        'referencia',
        'regiao',
    ];

    protected $casts = [
        'data' => 'date',
        'referencia' => 'boolean',
    ];
}

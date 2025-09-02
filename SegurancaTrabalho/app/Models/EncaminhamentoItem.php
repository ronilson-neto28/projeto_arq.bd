<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncaminhamentoItem extends Model
{
    protected $table = 'encaminhamento_itens';

    protected $fillable = [
        'encaminhamento_id',
        'exame_id',
        'nome_snapshot',
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

    public function encaminhamento(): BelongsTo
    {
        return $this->belongsTo(Encaminhamento::class);
    }

    public function exame(): BelongsTo
    {
        return $this->belongsTo(Exame::class);
    }
}

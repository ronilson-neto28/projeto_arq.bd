<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncaminhamentoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'encaminhamento_id',
        'procedimento',     // snapshot do nome do catálogo
        'justificativa',
        'data',
        'hora',
        'prestador',
        'status',           // solicitado|agendado|realizado|recebido
        'instrucoes',
        'laudo_path',
        'resultado',
        'referencia',       // audiometria de referência
        'regiao',           // RX: região/posição
    ];

    protected $casts = [
        'data'       => 'date',
        'referencia' => 'boolean',
    ];

    public function encaminhamento(): BelongsTo
    {
        return $this->belongsTo(Encaminhamento::class);
    }
}

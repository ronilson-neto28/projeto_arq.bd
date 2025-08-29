<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Encaminhamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_guia',
        'data_emissao',
        'medico_solicitante',
        'funcionario_id',
        'empresa_id',
        'cargo_id',
        'tipo_exame',           // Admissional/Periódico/Demissional/Retorno/Mudança de Função
        'data_atendimento',
        'hora_atendimento',
        'riscos_extra_json',    // array de riscos adicionais
        'observacoes',
        'previsao_retorno',
        'status',               // agendado/realizado/faltou/cancelado
        'local_clinica_id',     // opcional: local ou clínica
        'medico_responsavel_id', // opcional: médico responsável
        'responsavel_marcacao',
        'escopo_registro',      // solicitacao | solicitacao_conclusao
    ];

    protected $casts = [
        'data_emissao'      => 'date',
        'data_atendimento'  => 'date',
        'previsao_retorno'  => 'date',
        'riscos_extra_json' => 'array',
    ];

    public function itens(): HasMany
    {
        return $this->hasMany(EncaminhamentoItem::class);
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
}

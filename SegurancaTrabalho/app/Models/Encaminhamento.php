<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Subdocument\EncaminhamentoItem;

class Encaminhamento extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'encaminhamentos';

    protected $fillable = [
        'empresa_id',
        'funcionario_id',
        'cargo_id',
        'tipo_exame',
        'data_atendimento',
        'hora_atendimento',
        'status',
        'observacoes',
        'previsao_retorno',
        'local_clinica',
        'medico',
        'riscos_ids',
    ];

    protected $casts = [
        'data_atendimento' => 'datetime',
        'previsao_retorno' => 'datetime',
        'riscos_ids' => 'array',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function itens()
    {
        return $this->embedsMany(EncaminhamentoItem::class, 'encaminhamentos_itens');
    }

    public function itensSolicitados()
    {
        return $this->embedsMany(EncaminhamentoItem::class, 'itens_solicitados');
    }

    public function riscosSelecionados()
    {
        return \App\Models\Risco::query()->whereIn('_id', (array)($this->riscos_ids ?? []));
    }
}

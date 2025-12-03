<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Subdocument\RiscoExame;

class Risco extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'riscos';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nome',
        'tipo_risco_id',
        'descricao',
        'limite_tolerancia',
    ];

    public function tipoRisco()
    {
        return $this->belongsTo(TipoDeRisco::class, 'tipo_risco_id');
    }

    public function examesObrigatorios()
    {
        return $this->embedsMany(RiscoExame::class, 'risco_exame');
    }
}

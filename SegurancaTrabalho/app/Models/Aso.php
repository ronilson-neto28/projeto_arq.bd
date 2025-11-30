<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Aso extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'asos';

    protected $fillable = [
        'encaminhamento_id',
        'data_emissao',
        'resultado',
        'medico_emissor_id',
    ];

    protected $casts = [
        'data_emissao' => 'date',
    ];

    public function encaminhamento()
    {
        return $this->belongsTo(Encaminhamento::class, 'encaminhamento_id');
    }
}


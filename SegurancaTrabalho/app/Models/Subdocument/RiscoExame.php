<?php

namespace App\Models\Subdocument;

use MongoDB\Laravel\Eloquent\Model;

class RiscoExame extends Model
{
    protected $fillable = [
        'exame_id',
        'periodicidade_meses',
        'obrigatorio_admissional',
    ];

    public function exame()
    {
        return $this->belongsTo(\App\Models\Exame::class, 'exame_id');
    }
}


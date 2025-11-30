<?php

namespace App\Models\Subdocument;

use MongoDB\Laravel\Eloquent\Model;

class CargoRisco extends Model
{
    protected $fillable = [
        'risco_id',
        'tipo_risco_id',
        'grau',
    ];

    public function risco()
    {
        return $this->belongsTo(\App\Models\Risco::class, 'risco_id');
    }
}

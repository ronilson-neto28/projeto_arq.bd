<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class TipoDeRisco extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'tipos_de_risco';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nome',
        'cor',
        'descricao',
    ];
}


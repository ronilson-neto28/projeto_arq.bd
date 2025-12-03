<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Exame extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'exames';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nome',
        'codigo',
        'tipo_procedimento',
        'descricao',
    ];
}

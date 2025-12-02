<?php

namespace App\Models\Subdocument;

use MongoDB\Laravel\Eloquent\Model;

class Telefone extends Model
{
    protected $fillable = [
        'numero',
        'tipo',
        'contato',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiscoExame extends Model
{
    protected $table = 'risco_exame';

    protected $fillable = [
        'risco_id',
        'exame_id',
        'periodicidade_meses',
    ];
}

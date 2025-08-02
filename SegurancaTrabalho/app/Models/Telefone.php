<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
    protected $fillable = ['numero', 'funcionario_id'];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
}

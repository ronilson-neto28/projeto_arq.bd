<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargoRisco extends Model
{
    public function riscos()
    {
        return $this->belongsToMany(Risco::class, 'cargo_risco')->withTimestamps();
    }
}

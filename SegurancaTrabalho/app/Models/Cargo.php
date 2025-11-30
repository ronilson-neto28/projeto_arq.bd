<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Subdocument\CargoRisco as CargoRiscoSubdoc;

class Cargo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cargos';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nome',
        'cbo',
        'descricao',
    ];

    

    public function riscos()
    {
        return $this->embedsMany(CargoRiscoSubdoc::class, 'cargo_risco');
    }
}

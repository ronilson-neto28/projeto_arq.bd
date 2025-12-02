<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Subdocument\CargoRisco as CargoRiscoSubdoc;
use App\Models\Empresa;

class Cargo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cargos';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nome',
        'cbo',
        'descricao',
        'empresa_id',
    ];

    

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function riscos()
    {
        return $this->embedsMany(CargoRiscoSubdoc::class, 'cargo_risco');
    }
}

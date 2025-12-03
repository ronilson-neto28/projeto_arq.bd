<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aso extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'asos';
    protected $primaryKey = '_id';

    protected $fillable = [
        'encaminhamento_id',
        'data_emissao',
        'data_validade',
        'resultado',
        'medico_emissor_id',
        'aptidao_detalhada',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'data_validade' => 'date',
    ];

    public function encaminhamento()
    {
        return $this->belongsTo(Encaminhamento::class, 'encaminhamento_id');
    }
}

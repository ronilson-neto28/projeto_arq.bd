<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'categoria',           // Clínica | Laboratório | Imagem | Audiometria
        'prestador_padrao',
        'flag_audiometria',
        'flag_rx_com_regiao',
        'periodicidade_meses',
        'instrucoes_padrao',
    ];

    protected $casts = [
        'flag_audiometria'   => 'boolean',
        'flag_rx_com_regiao' => 'boolean',
        'periodicidade_meses'=> 'integer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aso extends Model
{
    protected $table = 'asos';

    protected $fillable = [
        'encaminhamento_id',
        'aptidao',
        'data_emissao',
        'medico',
        'arquivo_pdf_path',
    ];

    protected $casts = [
        'data_emissao' => 'date',
    ];

    public function encaminhamento(): BelongsTo
    {
        return $this->belongsTo(Encaminhamento::class);
    }
}

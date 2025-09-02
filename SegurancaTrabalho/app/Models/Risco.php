<?php
// app/Models/Risco.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Risco extends Model
{
    protected $table = 'riscos';
    protected $fillable = ['tipo_de_risco_id','nome','descricao'];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoDeRisco::class, 'tipo_de_risco_id');
    }

    public function cargos(): BelongsToMany
    {
        return $this->belongsToMany(Cargo::class, 'cargo_risco')->withTimestamps();
    }
}

<?php
// app/Models/TipoDeRisco.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDeRisco extends Model
{
    protected $table = 'tipos_de_risco';
    protected $fillable = ['nome'];

    public function riscos(): HasMany
    {
        return $this->hasMany(Risco::class, 'tipo_de_risco_id');
    }
}

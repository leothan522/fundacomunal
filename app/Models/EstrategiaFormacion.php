<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstrategiaFormacion extends Model
{
    use SoftDeletes;
    protected $table = 'estrategias_formacion';
    protected $fillable = [
        'nombre',
    ];

    public function formacion(): HasMany
    {
        return $this->hasMany(Formacion::class, 'estrategias_formacion_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPoblacion extends Model
{
    use SoftDeletes;
    protected $table = 'tipos_poblacion';
    protected $fillable = [
        'nombre',
    ];

    public function consejos(): HasMany
    {
        return $this->hasMany(ConsejoComunal::class, 'tipos_poblacion_id', 'id');
    }

}

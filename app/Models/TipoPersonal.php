<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPersonal extends Model
{
    use SoftDeletes;
    protected $table = 'tipos_personal';
    protected $fillable = [
        'nombre',
    ];

    public function trabajadores(): HasMany
    {
        return $this->hasMany(GestionHumana::class, 'tipos_personal_id', 'id');
    }

}

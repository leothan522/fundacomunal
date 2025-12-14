<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoObpp extends Model
{
    use SoftDeletes;
    protected $table = 'tipos_obpp';
    protected $fillable = [
        'nombre',
        'fortalecimiento'
    ];

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'tipos_obpp_id', 'id');
    }

    public function formacion(): HasMany
    {
        return $this->hasMany(Formacion::class, 'tipos_obpp_id', 'id');
    }

    public function fortalecimiento(): HasMany
    {
        return $this->hasMany(Fortalecimiento::class, 'tipos_obpp_id', 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Redi extends Model
{
    use SoftDeletes;
    protected $table = 'redis';
    protected $fillable = [
        'nombre'
    ];

    public function estados(): HasMany
    {
        return $this->hasMany(Estado::class, 'redis_id', 'id');
    }

    public function trabajadores(): HasMany
    {
        return $this->hasMany(GestionHumana::class, 'redis_id', 'id');
    }

    public function comunas(): HasMany
    {
        return $this->hasMany(Comuna::class, 'redis_id', 'id');
    }

    public function consejos(): HasMany
    {
        return $this->hasMany(ConsejoComunal::class, 'redis_id', 'id');
    }

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'redis_id', 'id');
    }

}

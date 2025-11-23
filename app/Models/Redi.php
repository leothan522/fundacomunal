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

}

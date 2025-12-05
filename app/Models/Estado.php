<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use SoftDeletes;
    protected $table = 'estados';
    protected $fillable = [
        'nombre',
        'redis_id',
    ];

    public function redi(): BelongsTo
    {
        return $this->belongsTo(Redi::class, 'redis_id', 'id');
    }

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class, 'estados_id', 'id');
    }

    public function trabajadores(): HasMany
    {
        return $this->hasMany(GestionHumana::class, 'estados_id', 'id');
    }

    public function comunas(): HasMany
    {
        return $this->hasMany(Comuna::class, 'estados_id', 'id');
    }

    public function consejos(): HasMany
    {
        return $this->hasMany(ConsejoComunal::class, 'estados_id', 'id');
    }

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'estados_id', 'id');
    }

}

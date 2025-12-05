<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use SoftDeletes;
    protected $table = 'municipios';
    protected $fillable = [
        'nombre',
        'nombre_real',
        'nombre_cne',
        'estados_id',
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estados_id', 'id');
    }

    public function trabajadores(): HasMany
    {
        return $this->hasMany(GestionHumana::class, 'municipios_id', 'id');
    }

    public function comunas(): HasMany
    {
        return $this->hasMany(Comuna::class, 'municipios_id', 'id');
    }

    public function consejos(): HasMany
    {
        return $this->hasMany(ConsejoComunal::class, 'municipios_id', 'id');
    }

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'municipios_id', 'id');
    }

}

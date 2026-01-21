<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comuna extends Model
{
    use SoftDeletes;
    protected $table = 'comunas';
    protected $fillable = [
        'nombre',
        'cod_com',
        'cod_situr',
        'cantidad_cc',
        'tipo_obpp',
        'redis_id',
        'estados_id',
        'municipios_id',
        'parroquia',
    ];

    public function redi(): BelongsTo
    {
        return $this->belongsTo(Redi::class, 'redis_id', 'id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estados_id', 'id');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipios_id', 'id');
    }

    public function consejos(): HasMany
    {
        return $this->hasMany(ConsejoComunal::class, 'comunas_id', 'id');
    }

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'comunas_id', 'id');
    }

    public function formacion(): HasMany
    {
        return $this->hasMany(Formacion::class, 'comunas_id', 'id');
    }

    public function fortalecimiento(): HasMany
    {
        return $this->hasMany(Fortalecimiento::class, 'comunas_id', 'id');
    }

}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsejoComunal extends Model
{
    use SoftDeletes;
    protected $table = 'consejos_comunales';
    protected $fillable = [
        'nombre',
        'situr_viejo',
        'situr_nuevo',
        'tipos_poblacion_id',
        'fecha_asamblea',
        'fecha_vencimiento',
        'comunas_id',
        'redis_id',
        'estados_id',
        'municipios_id',
        'parroquia',
        'is_eleccion',
        'fecha_eleccion',
    ];

    protected $casts = [
        'is_eleccion' => 'boolean',
        'fecha_eleccion' => 'date',
        'fecha_asamblea' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (ConsejoComunal $consejoComunal) {
            // Si tiene fecha de vencimiento y esta fecha NO ha pasado (es VIGENTE)
            if ($consejoComunal->fecha_vencimiento && ! Carbon::parse($consejoComunal->fecha_vencimiento)->isPast()) {
                // Si tenía activado el flag de elección, lo limpiamos automáticamente
                if ($consejoComunal->is_eleccion) {
                    $consejoComunal->is_eleccion = false;
                    $consejoComunal->fecha_eleccion = null;
                }
            }
        });
    }

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

    public function comuna(): BelongsTo
    {
        return $this->belongsTo(Comuna::class, 'comunas_id', 'id');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoPoblacion::class, 'tipos_poblacion_id', 'id');
    }

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'consejos_comunales_id', 'id');
    }

    public function formacion(): HasMany
    {
        return $this->hasMany(Formacion::class, 'consejos_comunales_id', 'id');
    }

    public function fortalecimiento(): HasMany
    {
        return $this->hasMany(Fortalecimiento::class, 'consejos_comunales_id', 'id');
    }

}

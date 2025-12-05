<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participacion extends Model
{
    use SoftDeletes;
    protected $table = 'actividades_participacion';
    protected $fillable = [
        'fecha',
        'redis_id',
        'estados_id',
        'municipios_id',
        'parroquia',
        'localidad',
        'cantidad_cc',
        'tipos_obpp_id',
        'situr_obpp',
        'nombre_obpp',
        'tipos_poblacion_id',
        'areas_items_id',
        'areas_procesos_id',
        'cantidad_familias',
        'cantidad_asistentes',
        'vocero_nombre',
        'vocero_telefono',
        'gestion_humana_id',
        'observacion',
        'comunas_id',
        'consejos_comunales_id',
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

    public function obpp(): BelongsTo
    {
        return $this->belongsTo(TipoObpp::class, 'tipos_obpp_id', 'id');
    }

    public function poblacion(): BelongsTo
    {
        return $this->belongsTo(TipoPoblacion::class, 'tipos_poblacion_id', 'id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(AreaItem::class, 'areas_items_id', 'id');
    }

    public function proceso(): BelongsTo
    {
        return $this->belongsTo(AreaProceso::class, 'areas_procesos_id', 'id');
    }

    public function promotor(): BelongsTo
    {
        return $this->belongsTo(GestionHumana::class, 'gestion_humana_id', 'id');
    }

    public function comuna(): BelongsTo
    {
        return $this->belongsTo(Comuna::class, 'comunas_id', 'id');
    }

    public function consejo(): BelongsTo
    {
        return $this->belongsTo(ConsejoComunal::class, 'consejos_comunales_id', 'id');
    }

}

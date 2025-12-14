<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formacion extends Model
{
    use SoftDeletes;
    protected $table = 'actividades_formacion';
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
        'estrategias_formacion_id',
        'modalidades_formacion_id',
        'cantidad_mujeres',
        'cantidad_hombres',
        'medios_verificacion_id',
        'vocero_nombre',
        'vocero_telefono',
        'gestion_humana_id',
        'observacion',
        'comunas_id',
        'consejos_comunales_id',
        'users_id',
        'estatus',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function estrategia(): BelongsTo
    {
        return $this->belongsTo(EstrategiaFormacion::class, 'estrategias_formacion_id', 'id');
    }

    public function modalidad(): BelongsTo
    {
        return $this->belongsTo(ModalidadFormacion::class, 'modalidades_formacion_id', 'id');
    }

    public function medio(): BelongsTo
    {
        return $this->belongsTo(MedioVerificacion::class, 'medios_verificacion_id', 'id');
    }

}

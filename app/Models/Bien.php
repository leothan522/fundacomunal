<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bien extends Model
{
    use SoftDeletes;

    protected $table = 'bienes';

    protected $fillable = [
        'serial',
        'bienes_ubicaciones_id',
        'bienes_condiciones_id',
        'bienes_tipos_id',
        'bienes_marcas_id',
        'bienes_modelos_id',
        'bienes_colores_id',
        'informacion_adicional',
        'imagen_frontal',
        'imagen_posterior',
    ];

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(BienUbicacion::class, 'bienes_ubicaciones_id', 'id');
    }

    public function condicion(): BelongsTo
    {
        return $this->belongsTo(BienCondicion::class, 'bienes_condiciones_id', 'id');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(BienTipo::class, 'bienes_tipos_id', 'id');
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(BienMarca::class, 'bienes_marcas_id', 'id');
    }

    public function modelo(): BelongsTo
    {
        return $this->belongsTo(BienModelo::class, 'bienes_modelos_id', 'id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(BienColor::class, 'bienes_colores_id', 'id');
    }

}

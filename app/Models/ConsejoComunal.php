<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsejoComunal extends Model
{
    use SoftDeletes;
    protected $table = 'consejos_comunales';
    protected $fillable = [
        'nombre',
        'situr_viejo',
        'situr_nuevo',
        'tipo',
        'fecha_asamblea',
        'fecha_vencimiento',
        'comunas_id',
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

    public function comuna(): BelongsTo
    {
        return $this->belongsTo(Comuna::class, 'comunas_id', 'id');
    }

}

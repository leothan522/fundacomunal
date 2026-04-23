<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacaciones extends Model
{
    use SoftDeletes;
    protected $table = 'gestion_humana_vacaciones';
    protected $fillable = [
        'gestion_humana_id',
        'fecha_inicio',
        'fecha_fin',
        'fecha_reintegro',
        'dias',
        'periodo',
        'observaciones',
    ];

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(GestionHumana::class, 'gestion_humana_id', 'id');
    }

}

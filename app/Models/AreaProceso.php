<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaProceso extends Model
{
    protected $table = 'areas_procesos';
    protected $fillable = [
        'nombre',
        'items_id',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(AreaItem::class, 'items_id', 'id');
    }

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'areas_procesos_id', 'id');
    }

    public function formacion(): HasMany
    {
        return $this->hasMany(Formacion::class, 'areas_procesos_id', 'id');
    }

}

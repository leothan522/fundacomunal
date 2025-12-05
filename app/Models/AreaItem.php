<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaItem extends Model
{
    use SoftDeletes;
    protected $table = 'areas_items';
    protected $fillable = [
        'nombre',
        'areas_id',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'areas_id', 'id');
    }

    public function procesos(): HasMany
    {
        return $this->hasMany(AreaProceso::class, 'items_id', 'id');
    }

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'areas_items_id', 'id');
    }

}

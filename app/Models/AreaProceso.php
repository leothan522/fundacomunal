<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaProceso extends Model
{
    protected $table = 'areas_procesos';
    protected $fillable = [
        'nombre',
        'items_id',
    ];

    public function Item(): BelongsTo
    {
        return $this->belongsTo(AreaItem::class, 'items_id', 'id');
    }

}

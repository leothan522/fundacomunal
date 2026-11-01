<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BienCondicion extends Model
{
    protected $table = 'bienes_condiciones';

    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function bienes(): HasMany
    {
        return $this->hasMany(Bien::class, 'bienes_condiciones_id', 'id');
    }
}

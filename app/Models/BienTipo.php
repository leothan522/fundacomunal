<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BienTipo extends Model
{
    protected $table = 'bienes_tipos';

    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function bienes(): HasMany
    {
        return $this->hasMany(Bien::class, 'bienes_tipos_id', 'id');
    }
}

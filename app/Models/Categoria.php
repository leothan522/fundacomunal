<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;
    protected $table = 'categorias';
    protected $fillable = [
        'nombre',
    ];

    public function trabajadores(): HasMany
    {
        return $this->hasMany(GestionHumana::class, 'categorias_id', 'id');
    }

}

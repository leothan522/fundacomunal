<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etapa extends Model
{
    use SoftDeletes;
    protected $table = 'etapas';
    protected $fillable = [
        'nombre',
    ];

    public function fortalecimiento(): HasMany
    {
        return $this->hasMany(Fortalecimiento::class, 'etapas_id', 'id');
    }

}

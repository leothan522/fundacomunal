<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModalidadFormacion extends Model
{
    use SoftDeletes;
    protected $table = 'modalidades_formacion';
    protected $fillable = [
        'nombre',
    ];

    public function formacion(): HasMany
    {
        return $this->hasMany(Formacion::class, 'modalidades_formacion_id', 'id');
    }

}

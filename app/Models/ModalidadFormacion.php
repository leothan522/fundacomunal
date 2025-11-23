<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModalidadFormacion extends Model
{
    use SoftDeletes;
    protected $table = 'modalidades_formacion';
    protected $fillable = [
        'nombre',
    ];
}

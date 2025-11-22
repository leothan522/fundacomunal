<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPersonal extends Model
{
    use SoftDeletes;
    protected $table = 'tipos_personal';
    protected $fillable = [
        'nombre',
    ];
}

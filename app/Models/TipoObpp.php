<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoObpp extends Model
{
    use SoftDeletes;
    protected $table = 'tipos_obpp';
    protected $fillable = [
        'nombre',
    ];
}

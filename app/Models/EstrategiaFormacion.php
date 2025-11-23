<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstrategiaFormacion extends Model
{
    use SoftDeletes;
    protected $table = 'estrategias_formacion';
    protected $fillable = [
        'nombre',
    ];
}

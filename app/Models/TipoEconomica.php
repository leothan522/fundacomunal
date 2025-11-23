<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEconomica extends Model
{
    use SoftDeletes;
    protected $table = 'tipos_economicas';
    protected $fillable = [
        'nombre',
    ];
}

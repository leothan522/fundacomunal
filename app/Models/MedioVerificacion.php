<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedioVerificacion extends Model
{
    use SoftDeletes;
    protected $table = 'medios_verificacion';
    protected $fillable = [
        'nombre',
    ];
}

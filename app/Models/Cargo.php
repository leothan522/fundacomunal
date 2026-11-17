<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    protected $table = "gestion_humana_cargos";
    protected $fillable = ["cargo", "estatus"];

    public function trabajador(): HasMany
    {
        return $this->hasMany(GestionHumana::class, 'cargos_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GestionHumana extends Model
{
    use SoftDeletes;
    protected $table = 'gestion_humana';
    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'telefono',
        'email',
        'tipos_personal_id',
        'categorias_id',
        'ente',
        'redis_id',
        'estados_id',
        'municipios_id',
        'parroquia',
        'observacion',
        'fecha_nacimiento',
        'fecha_ingreso',
        'users_id',
    ];

    public function tipoPersonal(): BelongsTo
    {
        return $this->belongsTo(TipoPersonal::class, 'tipos_personal_id', 'id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categorias_id', 'id');
    }

    public function redi(): BelongsTo
    {
        return $this->belongsTo(Redi::class, 'redis_id', 'id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estados_id', 'id');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipios_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function participacion(): HasMany
    {
        return $this->hasMany(Participacion::class, 'gestion_humana_id', 'id');
    }

}

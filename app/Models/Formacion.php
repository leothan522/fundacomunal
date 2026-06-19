<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formacion extends Model
{
    use SoftDeletes;
    protected $table = 'actividades_formacion';
    protected $fillable = [
        'fecha',
        'redis_id',
        'estados_id',
        'municipios_id',
        'parroquia',
        'localidad',
        'cantidad_cc',
        'tipos_obpp_id',
        'situr_obpp',
        'nombre_obpp',
        'tipos_poblacion_id',
        'areas_items_id',
        'areas_procesos_id',
        'estrategias_formacion_id',
        'modalidades_formacion_id',
        'cantidad_mujeres',
        'cantidad_hombres',
        'medios_verificacion_id',
        'vocero_nombre',
        'vocero_telefono',
        'gestion_humana_id',
        'observacion',
        'comunas_id',
        'consejos_comunales_id',
        'users_id',
        'estatus',
    ];

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

    public function obpp(): BelongsTo
    {
        return $this->belongsTo(TipoObpp::class, 'tipos_obpp_id', 'id');
    }

    public function poblacion(): BelongsTo
    {
        return $this->belongsTo(TipoPoblacion::class, 'tipos_poblacion_id', 'id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(AreaItem::class, 'areas_items_id', 'id')->withTrashed();
    }

    public function proceso(): BelongsTo
    {
        return $this->belongsTo(AreaProceso::class, 'areas_procesos_id', 'id')->withTrashed();
    }

    public function promotor(): BelongsTo
    {
        return $this->belongsTo(GestionHumana::class, 'gestion_humana_id', 'id')->withTrashed();
    }

    public function comuna(): BelongsTo
    {
        return $this->belongsTo(Comuna::class, 'comunas_id', 'id');
    }

    public function consejo(): BelongsTo
    {
        return $this->belongsTo(ConsejoComunal::class, 'consejos_comunales_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id')->withTrashed();
    }

    public function estrategia(): BelongsTo
    {
        return $this->belongsTo(EstrategiaFormacion::class, 'estrategias_formacion_id', 'id');
    }

    public function modalidad(): BelongsTo
    {
        return $this->belongsTo(ModalidadFormacion::class, 'modalidades_formacion_id', 'id');
    }

    public function medio(): BelongsTo
    {
        return $this->belongsTo(MedioVerificacion::class, 'medios_verificacion_id', 'id');
    }

    public function generarMensajeWhatsApp(): string
    {
        $fechaFormateada = date('d/m/Y', strtotime($this->fecha));

        // Escribimos los emojis adaptados al módulo de Formación
        $ico_reporte   = "📢"; // Sombrero de graduación para Formación
        $ico_fecha     = "📅";
        $ico_promotor  = "👤";
        $ico_ubicacion = "📍";
        $ico_municipio = "📌";
        $ico_comuna    = "🏡";
        $ico_consejo   = "👥";
        $ico_item      = "📋";
        $ico_estrategia = "💡"; // Foco para la Estrategia/Modalidad
        $ico_poblacion = "👥";
        $ico_mujeres   = "👩";
        $ico_hombres   = "👨";
        $ico_obpp      = "🏢";
        $ico_vocero    = "📱";
        $ico_obs       = "📝";

        $shortName = $this->promotor?->shortName ?? 'No asignado';

        $texto = "{$ico_reporte} *REPORTE DE ACTIVIDAD - FORMACIÓN FUNDACOMUNAL*\n\n";
        $texto .= "{$ico_fecha} *Fecha:* {$fechaFormateada}\n";
        $texto .= "{$ico_promotor} *Promotor:* {$shortName}\n";
        $texto .= "{$ico_ubicacion} *Ubicación:* Parroquia {$this->parroquia}, Sector {$this->localidad}\n";
        $texto .= "{$ico_municipio} *Municipio:* " . ($this->municipio?->nombre ?? 'N/A') . "\n";

        if ($this->comuna) {
            $texto .= "{$ico_comuna} *Comuna:* {$this->comuna->nombre}\n";
        }
        if ($this->consejo) {
            $texto .= "{$ico_consejo} *Consejo Comunal:* {$this->consejo->nombre}\n";
        }

        $texto .= "{$ico_item} *Área/Proceso:* " . ($this->proceso?->nombre ?? 'N/A') . " (" . ($this->area?->nombre ?? 'N/A') . ")\n";

        // Datos específicos de Formación: Estrategia y Modalidad
        $detallesFormacion = [];
        if ($this->estrategia?->nombre) {
            $detallesFormacion[] = $this->estrategia->nombre;
        }
        if ($this->modalidad?->nombre) {
            $detallesFormacion[] = $this->modalidad->nombre;
        }
        if (!empty($detallesFormacion)) {
            $texto .= "{$ico_estrategia} *Metodología:* " . implode(' - ', $detallesFormacion) . "\n";
        }

        if ($this->poblacion?->nombre) {
            $texto .= "{$ico_poblacion} *Población Atendida:* {$this->poblacion->nombre}\n";
        }

        // Conteo de participantes desglosado por género
        if (($this->cantidad_mujeres && $this->cantidad_mujeres > 0) || ($this->cantidad_hombres && $this->cantidad_hombres > 0)) {
            $asistentes = [];
            if ($this->cantidad_mujeres > 0) {
                $asistentes[] = "{$ico_mujeres} {$this->cantidad_mujeres} Mujeres";
            }
            if ($this->cantidad_hombres > 0) {
                $asistentes[] = "{$ico_hombres} {$this->cantidad_hombres} Hombres";
            }
            $texto .= "👥 *Participantes:* " . implode(' / ', $asistentes) . "\n";
        }

        // Mostramos la OBPP con su tipo y su código SITUR (usando situr_obpp directo del modelo)
        if ($this->nombre_obpp) {
            $texto .= "{$ico_obpp} *OBPP:* {$this->nombre_obpp}";

            $detallesObpp = [];
            if ($this->obpp?->nombre) {
                $detallesObpp[] = $this->obpp->nombre;
            }
            if ($this->situr_obpp) {
                $detallesObpp[] = "SITUR: " . $this->situr_obpp;
            }

            if (!empty($detallesObpp)) {
                $texto .= " (" . implode(' - ', $detallesObpp) . ")";
            }
            $texto .= "\n";
        }

        if (!empty(trim($this->vocero_nombre))) {
            $texto .= "{$ico_vocero} *Vocero:* {$this->vocero_nombre}";
            if (!empty(trim($this->vocero_telefono))) {
                $texto .= " ({$this->vocero_telefono})";
            }
            $texto .= "\n";
        }

        if (!empty(trim($this->observacion))) {
            $texto .= "\n{$ico_obs} *Observaciones:* {$this->observacion}\n";
        }

        return rawurlencode($texto);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fortalecimiento extends Model
{
    use SoftDeletes;
    protected $table = 'actividades_fortalecimiento';
    protected $fillable = [
        'fecha',
        'redis_id',
        'estados_id',
        'municipios_id',
        'parroquia',
        'localidad',
        'nombre_osp',
        'rif_osp',
        'situr_obpp',
        'nombre_obpp',
        'areas_items_id',
        'areas_procesos_id',
        'tipos_obpp_id',
        'tipos_economicas_id',
        'cantidad_personas',
        'cantidad_familias',
        'descripcion_proyecto',
        'etapas_id',
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

    public function medio(): BelongsTo
    {
        return $this->belongsTo(MedioVerificacion::class, 'medios_verificacion_id', 'id');
    }

    public function economica(): BelongsTo
    {
        return $this->belongsTo(TipoEconomica::class, 'tipos_economicas_id', 'id');
    }

    public function etapa(): BelongsTo
    {
        return $this->belongsTo(Etapa::class, 'etapas_id', 'id');
    }

    public function generarMensajeWhatsApp(): string
    {
        $fechaFormateada = date('d/m/Y', strtotime($this->fecha));

        // Escribimos los emojis adaptados al módulo de Fortalecimiento
        $ico_reporte   = "📢"; // Crecimiento/Fortalecimiento
        $ico_fecha     = "📅";
        $ico_promotor  = "👤";
        $ico_ubicacion = "📍";
        $ico_municipio = "🗺️";
        $ico_comuna    = "🏡";
        $ico_consejo   = "👥";
        $ico_item      = "📋";
        $ico_osp       = "🏭"; // Fábrica/Empresa para la OSP
        $ico_proyecto  = "📜"; // Documento para el Proyecto
        $ico_familias  = "👨‍👩‍👧‍👦";
        $ico_personas  = "👥";
        $ico_obpp      = "🏢";
        $ico_vocero    = "📱";
        $ico_obs       = "📝";

        $shortName = $this->promotor?->shortName ?? 'No asignado';

        $texto = "{$ico_reporte} *REPORTE DE ACTIVIDAD - FORTALECIMIENTO FUNDACOMUNAL*\n\n";
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

        // Datos de la Organización Socioproductiva (OSP) y Actividad Económica
        if ($this->nombre_osp) {
            $texto .= "{$ico_osp} *OSP:* {$this->nombre_osp}";
            if ($this->rif_osp) {
                $texto .= " (RIF: {$this->rif_osp})";
            }
            if ($this->economica?->nombre) {
                $texto .= " - Tipo: " . $this->economica->nombre;
            }
            $texto .= "\n";
        }

        // Datos del Proyecto y su Etapa actual
        if (!empty(trim($this->descripcion_proyecto))) {
            $texto .= "{$ico_proyecto} *Proyecto:* {$this->descripcion_proyecto}";
            if ($this->etapa?->nombre) {
                $texto .= " (Etapa: " . $this->etapa->nombre . ")";
            }
            $texto .= "\n";
        }

        // Impacto / Atendidos
        if (!empty($this->cantidad_familias) && $this->cantidad_familias > 0) {
            $texto .= "{$ico_familias} *Familias Atendidas:* {$this->cantidad_familias}\n";
        }
        if (!empty($this->cantidad_personas) && $this->cantidad_personas > 0) {
            $texto .= "{$ico_personas} *Personas Atendidas:* {$this->cantidad_personas}\n";
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

<?php

namespace App\Filament\Widgets;

use App\Models\Categoria;
use App\Models\GestionHumana;
use App\Models\Vacaciones;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class CategoriaWidget extends Widget
{
    protected string $view = 'filament.widgets.categoria-widget';
    protected static bool $isLazy = false;
    protected static ?int $sort = 2;

    public int $totalTrabajadores;
    public int $totalVacacionesActivas;
    public mixed $categorias;

    public function mount(): void
    {
        $hoy = Carbon::today()->toDateString();

        // 1. Total general de trabajadores
        $this->totalTrabajadores = GestionHumana::count();

        // 2. Obtener IDs de trabajadores con el nuevo sistema de fechas en curso
        $trabajadoresConVacacionesNuevas = Vacaciones::where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->pluck('gestion_humana_id')
            ->toArray();

        // 3. Obtener IDs de trabajadores que tienen la categoría "VACACIONES" de forma manual
        // Nota: Asegúrate de colocar el nombre exacto de la categoría vieja (ej. 'VACACIONES')
        $trabajadoresConVacacionesManuales = GestionHumana::whereHas('categoria', function ($query) {
            $query->where('nombre', 'like', '%VACACIONES%');
        })
            ->pluck('id')
            ->toArray();

        // 4. Fusionamos ambas listas y contamos los registros únicos (evita duplicados si alguien está en ambos)
        $todosEnVacaciones = array_unique(array_merge($trabajadoresConVacacionesNuevas, $trabajadoresConVacacionesManuales));
        $this->totalVacacionesActivas = count($todosEnVacaciones);

        // 5. Carga de categorías activas optimizada
        $this->categorias = Categoria::has('trabajadores')
            ->with(['trabajadores'])
            ->get();
    }

    public static function canView(): bool
    {
        return isAdmin() || auth()->user()->hasRole('GESTION HUMANA');
    }

}

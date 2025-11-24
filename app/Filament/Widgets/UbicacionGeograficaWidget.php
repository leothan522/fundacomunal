<?php

namespace App\Filament\Widgets;

use App\Models\GestionHumana;
use App\Models\Municipio;
use Filament\Widgets\Widget;

class UbicacionGeograficaWidget extends Widget
{
    protected string $view = 'filament.widgets.ubicacion-geografica-widget';
    protected static bool $isLazy = false;
    protected static ?int $sort = 3;

    public int $totalTrabajadores;
    public mixed $municipios;

    public function mount(): void
    {
        $this->totalTrabajadores = GestionHumana::count();
        $this->municipios = Municipio::has('trabajadores')->get();
    }

    public static function canView(): bool
    {
        return isAdmin() || auth()->user()->hasRole('GESTION HUMANA');
    }

}

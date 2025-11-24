<?php

namespace App\Filament\Widgets;

use App\Models\GestionHumana;
use App\Models\TipoPersonal;
use Filament\Widgets\Widget;

class GestionHumanaWidget extends Widget
{
    protected string $view = 'filament.widgets.gestion-humana-widget';
    protected static bool $isLazy = false;
    protected static ?int $sort = 1;

    public int $totalTrabajadores;
    public mixed $tipoPersonal;

    public function mount(): void
    {
        $this->totalTrabajadores = GestionHumana::count();
        $this->tipoPersonal = TipoPersonal::has('trabajadores')->get();
    }

    public static function canView(): bool
    {
        return isAdmin() || auth()->user()->hasRole('GESTION HUMANA');
    }
}

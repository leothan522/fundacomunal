<?php

namespace App\Filament\Widgets;

use App\Models\Categoria;
use App\Models\GestionHumana;
use Filament\Widgets\Widget;

class CategoriaWidget extends Widget
{
    protected string $view = 'filament.widgets.categoria-widget';
    protected static bool $isLazy = false;
    protected static ?int $sort = 2;

    public int $totalTrabajadores;
    public mixed $categorias;

    public function mount(): void
    {
        $this->totalTrabajadores = GestionHumana::count();
        $this->categorias = Categoria::has('trabajadores')->get();
    }

    public static function canView(): bool
    {
        return isAdmin() || auth()->user()->hasRole('GESTION HUMANA');
    }

}

<?php

namespace App\Filament\Widgets;

use App\Models\Comuna;
use App\Models\ConsejoComunal;
use App\Models\GestionHumana;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ObppWidget extends StatsOverviewWidget
{
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Circuitos o Comunas', $this->getComunas())
                ->description($this->getVinculados().' C.C. vinculados')
                //->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Consejos Comunales', $this->getConsejosCOmunales())
                ->description($this->getPorcentaje().'% en Circuitos o Comunas')
                //->descriptionIcon('heroicon-m-arrow-trending-down')
                //->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            Stat::make('Trabajadores', $this->getTrabajadores())
                ->description('56 promotores')
                //->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
        ];
    }

    public function getComunas(): string
    {
        return formatoMillares(Comuna::count(), 0);
    }

    public function getVinculados(): string
    {
        return formatoMillares(ConsejoComunal::has('comuna')->count(), 0);
    }

    public function getConsejosCOmunales(): string
    {
        return formatoMillares(ConsejoComunal::count(), 0);
    }

    public function getPorcentaje(): string
    {
        $totalConsejosComunales = ConsejoComunal::count();
        $vinculadosComunas = ConsejoComunal::has('comuna')->count();
        $porcentaje = ($vinculadosComunas / $totalConsejosComunales) * 100;
        $response = round($porcentaje, 2);
        return formatoMillares($response);
    }

    public function getTrabajadores()
    {
        return GestionHumana::count();
    }

}

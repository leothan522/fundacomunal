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
                ->description($this->getVinculados() . ' C.C. vinculados')
                ->color('success')
                ->url(route('filament.dashboard.resources.comunas.index'))
                ->extraAttributes(['onclick' => "Alpine.store('loader').show()"]),
            Stat::make('Consejos Comunales', $this->getConsejosCOmunales())
                ->description($this->getPorcentaje() . '% en Circuitos o Comunas')
                ->color('info')
                ->url(route('filament.dashboard.resources.consejos-comunales.index'))
                ->extraAttributes(['onclick' => "Alpine.store('loader').show()"]),
            Stat::make('Trabajadores', $this->getTrabajadores())
                ->description('56 promotores')
                ->color('primary')
                ->url(route('filament.dashboard.resources.gestion-humana.index'))
                ->extraAttributes(['onclick' => "Alpine.store('loader').show()"]),
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

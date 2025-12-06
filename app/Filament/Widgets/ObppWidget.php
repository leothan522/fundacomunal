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
                ->description($this->getPromotores().' promotores')
                ->color('primary')
                ->url(route('filament.dashboard.resources.gestion-humana.index'))
                ->visible(fn(): bool => isAdmin() || auth()->user()->hasRole('GESTION HUMANA'))
                ->extraAttributes(['onclick' => "Alpine.store('loader').show()"]),
            Stat::make('Planificación Semanal', 'Participación')
                ->description('45 actividades')
                ->color('primary')
                ->url(route('filament.dashboard.resources.participacion.index'))
                ->visible(fn(): bool => isAdmin() || auth()->user()->hasRole('PARTICIPACION'))
                ->extraAttributes(['onclick' => "Alpine.store('loader').show()"]),
            Stat::make('Planificación Semanal', 'Participacion')
                ->description('45 actividades')
                ->color('primary')
                ->url(route('filament.dashboard.resources.participacion.index'))
                ->hidden()
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
        $response = '0';
        $totalConsejosComunales = ConsejoComunal::count();
        $vinculadosComunas = ConsejoComunal::has('comuna')->count();
        if ($totalConsejosComunales > 0 && $vinculadosComunas > 0){
            $porcentaje = ($vinculadosComunas / $totalConsejosComunales) * 100;
            $response = round($porcentaje, 2);
        }

        return formatoMillares($response);
    }

    public function getTrabajadores(): int
    {
        return GestionHumana::count();
    }

    public function getPromotores(): int
    {
        return GestionHumana::whereRelation('tipoPersonal', 'nombre', 'PROMOTORES')->count();
    }

}

<?php

namespace App\Filament\Widgets;

use App\Models\Comuna;
use App\Models\ConsejoComunal;
use App\Models\Formacion;
use App\Models\GestionHumana;
use App\Models\Participacion;
use Carbon\Carbon;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

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
                ->description($this->getPromotores() . ' promotores')
                ->color('primary')
                ->url(route('filament.dashboard.resources.gestion-humana.index'))
                ->visible(fn(): bool => isAdmin() || auth()->user()->hasRole('GESTION HUMANA'))
                ->extraAttributes(['onclick' => "Alpine.store('loader').show()"]),
            Stat::make('Planificación Semanal', 'Participación')
                ->description(fn(): string => $this->getActividadesParticipacion() > 1 ? $this->getActividadesParticipacion().' actividades' : $this->getActividadesParticipacion().' actividad')
                ->color('primary')
                ->url(route('filament.dashboard.resources.participacion.index'))
                ->visible(fn(): bool => isAdmin() || auth()->user()->hasRole('PARTICIPACION'))
                ->extraAttributes(['onclick' => "Alpine.store('loader').show()"]),
            Stat::make('Planificación Semanal', 'Formación')
                ->description(fn(): string => $this->getActividadesFormacion() > 1 ? $this->getActividadesFormacion().' actividades' : $this->getActividadesFormacion().' actividad')
                ->color('primary')
                ->url(route('filament.dashboard.resources.formacion.index'))
                ->visible(fn(): bool => isAdmin() || auth()->user()->hasRole('FORMACION'))
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
        if ($totalConsejosComunales > 0 && $vinculadosComunas > 0) {
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

    public function getActividadesParticipacion(): int
    {
        $inicio = Carbon::now()->startOfWeek();
        $fin = Carbon::now()->endOfWeek();
        $query = Participacion::query();
        if (!isAdmin()) {
            $query->where(function (Builder $subQuery) {
                $subQuery->whereRelation('promotor', 'users_id', auth()->id())
                    ->orWhere('users_id', auth()->id());
            });

        }
        return $query->whereBetween('fecha', [$inicio, $fin])->count();
    }

    public function getActividadesFormacion(): int
    {
        $inicio = Carbon::now()->startOfWeek();
        $fin = Carbon::now()->endOfWeek();
        $query = Formacion::query();
        if (!isAdmin()) {
            $query->where(function (Builder $subQuery) {
                $subQuery->whereRelation('promotor', 'users_id', auth()->id())
                    ->orWhere('users_id', auth()->id());
            });

        }
        return $query->whereBetween('fecha', [$inicio, $fin])->count();
    }

}

<?php

namespace App\Filament\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class FechaFilter
{
    public static function filter()
    {
        return Filter::make('fecha')
            ->schema([
                Select::make('tipo_reporte')
                    ->label('Reporte')
                    ->options([
                        'semana-actual' => 'Semana Actual',
                        'semana-anterior' => 'Semana Anterior',
                        'semana-proxima' => 'Semana Próxima',
                        'mes-actual' => 'Mes Actual',
                        'mes-anterior' => 'Mes Anterior',
                    ])
            ])
            ->indicateUsing(function (array $data): ?string {
                if (!$data['tipo_reporte']) {
                    return null;
                }
                return 'Reporte: ' . Str::upper($data['tipo_reporte']);
            })
            ->query(function (Builder $query, array $data): Builder {

                if (!$data['tipo_reporte']) {
                    return $query;
                }

                $hoy = Carbon::today();
                $inicio = null;
                $fin = null;

                switch ($data['tipo_reporte']) {
                    case 'semana-actual':
                        $inicio = $hoy->copy()->startOfWeek();
                        $fin = $hoy->copy()->endOfWeek();
                        break;

                    case 'semana-anterior':
                        $inicio = $hoy->copy()->subWeek()->startOfWeek();
                        $fin = $hoy->copy()->subWeek()->endOfWeek();
                        break;

                    case 'semana-proxima':
                        $inicio = $hoy->copy()->addWeek()->startOfWeek();
                        $fin = $hoy->copy()->addWeek()->endOfWeek();
                        break;

                    case 'mes-actual':
                        $inicio = $hoy->copy()->startOfMonth();
                        $fin = $hoy->copy()->endOfMonth();
                        break;

                    case 'mes-anterior':
                        $inicio = $hoy->copy()->subMonth()->startOfMonth();
                        $fin = $hoy->copy()->subMonth()->endOfMonth();
                        break;
                }

                return $query->whereBetween('fecha', [$inicio, $fin]);

            });
    }
}

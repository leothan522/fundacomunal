<?php

namespace App\Filament\Resources\Parametros\Pages;

use App\Filament\Resources\Parametros\ParametroResource;
use App\Filament\Resources\Parametros\Widgets\ParametrosWidget;
use App\Models\Area;
use App\Models\AreaItem;
use App\Models\AreaProceso;
use App\Models\Parametro;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageParametros extends ManageRecords
{
    protected static string $resource = ParametroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('marzo2026')
                ->label('Actualización Marzo 2026')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (){
                    Actualizaciones::marzo2026();
                })
                ->hidden(fn():bool => Parametro::where('nombre', 'marzo_2026')->exists()),
            CreateAction::make()
                ->createAnother(false)
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ParametrosWidget::class
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 1;
    }

}

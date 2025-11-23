<?php

namespace App\Filament\Resources\Parametros\Pages;

use App\Filament\Resources\Parametros\ParametroResource;
use App\Filament\Resources\Parametros\Widgets\ParametrosWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageParametros extends ManageRecords
{
    protected static string $resource = ParametroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false),
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

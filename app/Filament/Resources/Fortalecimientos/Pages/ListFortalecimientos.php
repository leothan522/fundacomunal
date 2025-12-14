<?php

namespace App\Filament\Resources\Fortalecimientos\Pages;

use App\Filament\Resources\Fortalecimientos\FortalecimientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFortalecimientos extends ListRecords
{
    protected static string $resource = FortalecimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Actividad'),
        ];
    }
}

<?php

namespace App\Filament\Resources\GestionHumanas\Pages;

use App\Filament\Resources\GestionHumanas\GestionHumanaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGestionHumanas extends ListRecords
{
    protected static string $resource = GestionHumanaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Trabajador'),
        ];
    }
}

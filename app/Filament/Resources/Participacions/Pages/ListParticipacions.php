<?php

namespace App\Filament\Resources\Participacions\Pages;

use App\Filament\Resources\Participacions\ParticipacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParticipacions extends ListRecords
{
    protected static string $resource = ParticipacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Actividad'),
        ];
    }
}

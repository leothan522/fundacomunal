<?php

namespace App\Filament\Resources\Formacions\Pages;

use App\Filament\Resources\Formacions\FormacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFormacions extends ListRecords
{
    protected static string $resource = FormacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Actividad'),
        ];
    }
}

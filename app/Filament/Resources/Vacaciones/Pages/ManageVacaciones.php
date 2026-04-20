<?php

namespace App\Filament\Resources\Vacaciones\Pages;

use App\Filament\Resources\Vacaciones\VacacionesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageVacaciones extends ManageRecords
{
    protected static string $resource = VacacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false)
                ->modalWidth(Width::Small),
        ];
    }
}

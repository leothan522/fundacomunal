<?php

namespace App\Filament\Resources\Participacions\Pages;

use App\Filament\Resources\Participacions\ParticipacionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateParticipacion extends CreateRecord
{
    protected static string $resource = ParticipacionResource::class;
    protected static ?string $title = 'Crear Actividad';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['users_id'] = auth()->id();
        return $data;
    }
}

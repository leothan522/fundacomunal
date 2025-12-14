<?php

namespace App\Filament\Resources\Fortalecimientos\Pages;

use App\Filament\Resources\Fortalecimientos\FortalecimientoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFortalecimiento extends CreateRecord
{
    protected static string $resource = FortalecimientoResource::class;
    protected static ?string $title = 'Crear Actividad';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['users_id'] = auth()->id();
        return $data;
    }
}

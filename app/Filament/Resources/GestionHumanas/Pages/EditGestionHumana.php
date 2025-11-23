<?php

namespace App\Filament\Resources\GestionHumanas\Pages;

use App\Filament\Resources\GestionHumanas\GestionHumanaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditGestionHumana extends EditRecord
{
    protected static string $resource = GestionHumanaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

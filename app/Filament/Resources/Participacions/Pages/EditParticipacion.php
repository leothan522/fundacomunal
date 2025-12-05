<?php

namespace App\Filament\Resources\Participacions\Pages;

use App\Filament\Resources\Participacions\ParticipacionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditParticipacion extends EditRecord
{
    protected static string $resource = ParticipacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

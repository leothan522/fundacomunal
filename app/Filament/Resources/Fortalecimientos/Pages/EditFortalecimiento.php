<?php

namespace App\Filament\Resources\Fortalecimientos\Pages;

use App\Filament\Resources\Fortalecimientos\FortalecimientoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditFortalecimiento extends EditRecord
{
    protected static string $resource = FortalecimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

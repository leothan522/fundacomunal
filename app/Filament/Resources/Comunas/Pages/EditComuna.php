<?php

namespace App\Filament\Resources\Comunas\Pages;

use App\Filament\Resources\Comunas\ComunaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditComuna extends EditRecord
{
    protected static string $resource = ComunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

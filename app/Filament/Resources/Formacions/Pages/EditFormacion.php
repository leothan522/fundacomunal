<?php

namespace App\Filament\Resources\Formacions\Pages;

use App\Filament\Resources\Formacions\FormacionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditFormacion extends EditRecord
{
    protected static string $resource = FormacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

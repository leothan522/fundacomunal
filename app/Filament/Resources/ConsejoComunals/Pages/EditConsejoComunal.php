<?php

namespace App\Filament\Resources\ConsejoComunals\Pages;

use App\Filament\Resources\ConsejoComunals\ConsejoComunalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditConsejoComunal extends EditRecord
{
    protected static string $resource = ConsejoComunalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

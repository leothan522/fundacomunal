<?php

namespace App\Filament\Resources\EleccionSemanals\Pages;

use App\Filament\Resources\EleccionSemanals\EleccionSemanalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEleccionSemanal extends ViewRecord
{
    protected static string $resource = EleccionSemanalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

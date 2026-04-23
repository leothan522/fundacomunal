<?php

namespace App\Filament\Resources\EleccionSemanals\Pages;

use App\Filament\Resources\EleccionSemanals\EleccionSemanalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEleccionSemanals extends ListRecords
{
    protected static string $resource = EleccionSemanalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}

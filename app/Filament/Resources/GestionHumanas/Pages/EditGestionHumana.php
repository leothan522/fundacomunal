<?php

namespace App\Filament\Resources\GestionHumanas\Pages;

use App\Filament\Resources\GestionHumanas\GestionHumanaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditGestionHumana extends EditRecord
{
    protected static string $resource = GestionHumanaResource::class;

    public function getSubheading(): string|Htmlable|null
    {
        return $this->record ? $this->record->nombre.' '.$this->record->apellido : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

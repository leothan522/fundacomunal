<?php

namespace App\Filament\Resources\GestionHumanas\Pages;

use App\Filament\Resources\GestionHumanas\GestionHumanaResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Redirect;

class ListGestionHumanas extends ListRecords
{
    protected static string $resource = GestionHumanaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export-obpp')
                ->label('Exportar DATA')
                ->color('success')
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->requiresConfirmation()
                ->action(function () {
                    return Redirect::route('descargar.gestion-humana');
                })
                ->modalIcon(Heroicon::OutlinedDocumentArrowDown)
                ->modalDescription('El procedimiento tomará tiempo. No ejecute otras opciones hasta finalizar.'),
            CreateAction::make()
                ->label('Crear Trabajador'),
        ];
    }
}

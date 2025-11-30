<?php

namespace App\Filament\Resources\ConsejoComunals\Pages;

use App\Filament\Resources\ConsejoComunals\ConsejoComunalResource;
use App\Imports\ConsejoComunalImport;
use App\Models\ConsejoComunal;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Redirect;

class ListConsejoComunals extends ListRecords
{
    protected static string $resource = ConsejoComunalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export-obpp')
                ->label('Exportar DATA')
                ->color('success')
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->requiresConfirmation()
                ->action(function () {
                    return Redirect::route('descargar.data-obpp');
                })
                ->modalIcon(Heroicon::OutlinedDocumentArrowDown)
                ->modalDescription('El procedimiento tomará tiempo. No ejecute otras opciones hasta finalizar.')
                ->visible(isAdmin()),
            ExcelImportAction::make()
                ->use(ConsejoComunalImport::class)
                ->hidden(fn(): bool => ConsejoComunal::exists()),
            CreateAction::make(),
        ];
    }
}

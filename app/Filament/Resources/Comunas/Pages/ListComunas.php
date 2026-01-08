<?php

namespace App\Filament\Resources\Comunas\Pages;

use App\Filament\Resources\Comunas\ComunaResource;
use App\Imports\ComunaImport;
use App\Models\Comuna;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Redirect;
use Spatie\Browsershot\Browsershot;

class ListComunas extends ListRecords
{
    protected static string $resource = ComunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('prueba')
                ->label('Prueba Browsershot')
                ->action(function () {
                    $html = view('welcome')->render();
                    $path = storage_path('app/public/export-images/prueba.png');
                    Browsershot::html($html)
                        ->windowSize(800, 800)
                        ->save($path);
                    return response()->download($path);
                })
                ->visible(fn() => auth()->user()->is_root),
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
                ->use(ComunaImport::class)
                ->hidden(fn(): bool => Comuna::exists()),
            CreateAction::make(),
        ];
    }
}

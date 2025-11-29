<?php

namespace App\Filament\Resources\Comunas\Pages;

use App\Filament\Resources\Comunas\ComunaResource;
use App\Imports\ComunaImport;
use App\Models\Comuna;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Spatie\Browsershot\Browsershot;

class ListComunas extends ListRecords
{
    protected static string $resource = ComunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('prueba')
                ->action(function (){
                    $html = view('inicio')->render();
                    $path = storage_path('app/public/export-images/prueba.png');
                    Browsershot::html($html)
                        ->windowSize(800, 800)
                        ->save($path);
                    return response()->download($path);
                }),
            ExcelImportAction::make()
                ->use(ComunaImport::class)
                ->hidden(fn(): bool => Comuna::exists()),
            CreateAction::make(),
        ];
    }
}

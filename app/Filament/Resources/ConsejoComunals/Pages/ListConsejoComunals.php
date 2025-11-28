<?php

namespace App\Filament\Resources\ConsejoComunals\Pages;

use App\Filament\Resources\ConsejoComunals\ConsejoComunalResource;
use App\Imports\ConsejoComunalImport;
use App\Models\ConsejoComunal;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConsejoComunals extends ListRecords
{
    protected static string $resource = ConsejoComunalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->use(ConsejoComunalImport::class)
                ->hidden(fn(): bool => ConsejoComunal::exists()),
            CreateAction::make(),
        ];
    }
}

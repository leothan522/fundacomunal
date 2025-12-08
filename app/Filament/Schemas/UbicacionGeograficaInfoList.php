<?php

namespace App\Filament\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Str;

class UbicacionGeograficaInfoList
{
    public static function schema()
    {
        return Fieldset::make('Ubicación Geográfica')
            ->schema([
                TextEntry::make('redi.nombre')
                    ->label('REDI')
                    ->inlineLabel()
                    ->size(TextSize::Medium)
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->copyable(),
                TextEntry::make('estado.nombre')
                    ->label('Estado')
                    ->inlineLabel()
                    ->size(TextSize::Medium)
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->copyable(),
                TextEntry::make('municipio.nombre')
                    ->label('Municipio')
                    ->inlineLabel()
                    ->size(TextSize::Medium)
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->copyable(),
                TextEntry::make('parroquia')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->inlineLabel()
                    ->size(TextSize::Medium)
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->copyable(),
            ])
            ->columns(1);
    }
}

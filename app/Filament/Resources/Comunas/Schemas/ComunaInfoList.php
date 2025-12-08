<?php

namespace App\Filament\Resources\Comunas\Schemas;

use App\Filament\Schemas\UbicacionGeograficaInfoList;
use App\Models\Comuna;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class ComunaInfoList
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Datos Básicos')
                    ->schema([
                        TextEntry::make('nombre')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('cod_com')
                            ->label('COD. COM')
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('cod_situr')
                            ->label('COD. SITUR')
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('cc')
                            ->label('Cantidad C.C.')
                            ->default(fn(Comuna $record): int => $record->consejos->count())
                            ->numeric()
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                    ])
                    ->columns(1),
                UbicacionGeograficaInfoList::schema(),
                Section::make('Consejos Comunales')
                    ->schema([
                        RepeatableEntry::make('consejos')
                            ->schema([
                                TextEntry::make('nombre')
                                    ->wrap()
                                    ->bulleted()
                                    ->hiddenLabel()
                                    ->color('primary')
                                    ->size(TextSize::Medium)
                                    ->weight(FontWeight::Bold)
                                    ->copyable()
                            ])
                            ->contained(false)
                            ->hiddenLabel()
                    ])
                    ->compact()
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
            ]);
    }
}

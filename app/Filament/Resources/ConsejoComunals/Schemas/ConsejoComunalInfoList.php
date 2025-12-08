<?php

namespace App\Filament\Resources\ConsejoComunals\Schemas;

use App\Filament\Schemas\UbicacionGeograficaInfoList;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Str;

class ConsejoComunalInfoList
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Datos Básicos')
                    ->schema([
                        TextEntry::make('nombre')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('situr_viejo')
                            ->label('SITUR Viejo')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('situr_nuevo')
                            ->label('SITUR Nuevo')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('tipo.nombre')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('fecha_asamblea')
                            ->date()
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state): bool => empty($state)),
                        TextEntry::make('fecha_vencimiento')
                            ->date()
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state): bool => empty($state)),
                        TextEntry::make('comuna.nombre')
                            ->label('Circuito o Comuna')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn(?string $state): bool => empty($state)),
                    ])
                    ->columns(1),
                UbicacionGeograficaInfoList::schema(),
            ]);
    }
}

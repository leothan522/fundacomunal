<?php

namespace App\Filament\Resources\Biens\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Str;

class BienInfoList
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Información Principal')
                ->schema([
                    TextEntry::make('tipo.nombre')
                        ->label('Tipo / Categoría')
                        ->formatStateUsing(fn(string $state): string => Str::upper($state))
                        ->inlineLabel()
                        ->size(TextSize::Medium)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('serial')
                        ->label('Serial / ID')
                        ->formatStateUsing(fn(string $state): string => Str::upper($state))
                        ->inlineLabel()
                        ->size(TextSize::Medium)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('ubicacion.nombre')
                        ->label('Ubicación')
                        ->formatStateUsing(fn(string $state): string => Str::upper($state))
                        ->inlineLabel()
                        ->size(TextSize::Medium)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('condicion.nombre')
                        ->label('Condición')
                        ->formatStateUsing(fn(string $state): string => Str::upper($state))
                        ->inlineLabel()
                        ->size(TextSize::Medium)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                ])
                ->columns(1),
                Fieldset::make('Atributos')
                    ->schema([
                        TextEntry::make('marca.nombre')
                            ->label('Marca')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('modelo.nombre')
                            ->label('Modelo')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('color.nombre')
                            ->label('Color')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('informacion_adicional')
                            ->label('Información Adicional')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                    ])
                    ->columns(1)
            ]);
    }
}

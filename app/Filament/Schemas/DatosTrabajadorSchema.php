<?php

namespace App\Filament\Schemas;

use App\Models\GestionHumana;
use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Str;

class DatosTrabajadorSchema
{
    public static function schema(): array
    {
        return [
            Fieldset::make('Datos Básicos')
                ->schema([
                    TextEntry::make('cedula')
                        ->label('Cédula')
                        ->numeric()
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('nombre')
                        ->formatStateUsing(fn(string $state): string => Str::upper($state))
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('apellido')
                        ->formatStateUsing(fn(string $state): string => Str::upper($state))
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('telefono')
                        ->label('Teléfono')
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('email')
                        ->formatStateUsing(fn(string $state): string => Str::lower($state))
                        ->label(__('Email'))
                        ->default('-')
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable()
                        ->columnSpan(2),
                    TextEntry::make('fecha_nacimiento')
                        ->date()
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable()
                        ->hidden(fn(GestionHumana $record): bool => empty($record->fecha_nacimiento)),
                    TextEntry::make('edad')
                        ->state(fn(GestionHumana $record): ?string => $record->fecha_nacimiento ? Carbon::parse($record->fecha_nacimiento)->age . ' años' : null)
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable()
                        ->hidden(fn(GestionHumana $record): bool => empty($record->fecha_nacimiento)),
                ])
                ->columns(3)
                ->columnSpanFull(),
            Fieldset::make('Datos Laborales')
                ->schema([
                    TextEntry::make('tipoPersonal.nombre')
                        ->label('Labor que Ejerce')
                        ->inlineLabel()
                        ->size(TextSize::Medium)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('categoria.nombre')
                        ->label('Categoría')
                        ->default('-')
                        ->inlineLabel()
                        ->size(TextSize::Medium)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('ente')
                        ->formatStateUsing(fn(string $state): string => Str::upper($state))
                        ->label('Ente Adscrito')
                        ->inlineLabel()
                        ->size(TextSize::Medium)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable(),
                    TextEntry::make('fecha_ingreso')
                        ->date()
                        ->inlineLabel()
                        ->size(TextSize::Large)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable()
                        ->hidden(fn(GestionHumana $record): bool => empty($record->fecha_ingreso)),
                    TextEntry::make('observacion')
                        ->formatStateUsing(fn(string $state): string => Str::upper($state))
                        ->label('Observación')
                        ->inlineLabel()
                        ->size(TextSize::Medium)
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->copyable()
                        ->hidden(fn($state): bool => empty($state)),
                ])
                ->columns(1),
            UbicacionGeograficaFieldset::schema(),
        ];
    }
}

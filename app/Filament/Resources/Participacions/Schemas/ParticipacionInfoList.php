<?php

namespace App\Filament\Resources\Participacions\Schemas;

use App\Filament\Schemas\UbicacionGeograficaInfoList;
use App\Models\Participacion;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Str;

class ParticipacionInfoList
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Planificación')
                    ->dense()
                    ->schema([
                        TextEntry::make('fecha')
                            ->date()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('localidad')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('cantidad_cc')
                            ->label('C.C. participantes')
                            ->numeric()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('obpp.nombre')
                            ->label('Tipo OBPP')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('situr_obpp')
                            ->label('Código SITUR')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('nombre_obpp')
                            ->label('Nombre de la OBPP')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpan(2),
                        TextEntry::make('poblacion.nombre')
                            ->label('Tipo de C.C./Comuna')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('area.nombre')
                            ->label('Acompañamiento')
                            ->formatStateUsing(fn(string $state): string => Str::replace('_', ' ', $state))
                            ->size(TextSize::Small)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpanFull()
                            ->inlineLabel(),
                        TextEntry::make('proceso.nombre')
                            ->label('Proceso')
                            ->size(TextSize::Small)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpanFull()
                            ->inlineLabel(),
                        TextEntry::make('observacion')
                            ->label('Observación')
                            ->formatStateUsing(fn($state) => Str::upper($state))
                            ->size(TextSize::Small)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpanFull()
                            ->inlineLabel()
                            ->hidden(fn($state) => empty($state)),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                Fieldset::make('Datos de Contacto')
                    ->schema([
                        TextEntry::make('vocero_nombre')
                            ->label('Vocero')
                            ->formatStateUsing(fn($state) => Str::upper($state))
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->inlineLabel(),
                        TextEntry::make('vocero_telefono')
                            ->label('Teléfono')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->inlineLabel(),
                        TextEntry::make('promotor.nombre')
                            ->label('Promotor')
                            ->formatStateUsing(fn(Participacion $record) => Str::upper(strtok($record->promotor->nombre, ' ') . ' ' . strtok($record->promotor->apellido, ' ')))
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->inlineLabel(),
                        TextEntry::make('telefono')
                            ->label('Teléfono')
                            ->default(fn(Participacion $record) => $record->promotor->telefono)
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->inlineLabel(),
                    ])
                    ->columns(1),
                UbicacionGeograficaInfoList::schema(),
                Fieldset::make('Reporte de Actividad')
                    ->schema([
                        TextEntry::make('cantidad_familias')
                            ->label('Familias Beneficiadas')
                            ->numeric()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state) => empty($state)),
                        TextEntry::make('cantidad_asistentes')
                            ->label('Personas Asistentes')
                            ->numeric()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state) => empty($state)),
                        Text::make('SUSPENDIDA')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('info')
                            ->badge()
                            ->visible(fn(Participacion $record): bool => !$record->estatus)
                    ])
                    ->columns()
                    ->columnSpanFull()
                    ->visible(fn(Participacion $record): bool => !is_null($record->estatus))
            ]);
    }
}

<?php

namespace App\Filament\Resources\Fortalecimientos\Schemas;

use App\Filament\Schemas\UbicacionGeograficaInfoList;
use App\Models\Fortalecimiento;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Str;

class FortalecimientoInfolist
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
                        TextEntry::make('obpp.nombre')
                            ->label('Tipo de OSP')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpan(2),
                        TextEntry::make('nombre_osp')
                            ->label('Nombre de la OSP')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpan(2),
                        TextEntry::make('rif_osp')
                            ->label('RIF')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('economica.nombre')
                            ->label('Actividad economica')
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
                            ->label('Nombre de la OBPP vinculada')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpan(2),
                        TextEntry::make('etapa.nombre')
                            ->label('Etapa del proyecto')
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
                        TextEntry::make('descripcion_proyecto')
                            ->label('Descripción del proyecto')
                            ->formatStateUsing(fn($state) => Str::upper($state))
                            ->size(TextSize::Small)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpanFull()
                            ->inlineLabel(),
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
                            ->formatStateUsing(fn(Fortalecimiento $record) => Str::upper(strtok($record->promotor->nombre, ' ') . ' ' . strtok($record->promotor->apellido, ' ')))
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->inlineLabel(),
                        TextEntry::make('telefono')
                            ->label('Teléfono')
                            ->default(fn(Fortalecimiento $record) => $record->promotor->telefono)
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
                        TextEntry::make('cantidad_personas')
                            ->label('Personas Asistentes')
                            ->numeric()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state) => empty($state)),
                        TextEntry::make('medio.nombre')
                            ->label('Medio de Verificación')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state) => empty($state)),
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
                        Text::make('SUSPENDIDA')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('info')
                            ->badge()
                            ->visible(fn(Fortalecimiento $record): bool => !$record->estatus)
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->visible(fn(Fortalecimiento $record): bool => !is_null($record->estatus))
            ]);
    }
}

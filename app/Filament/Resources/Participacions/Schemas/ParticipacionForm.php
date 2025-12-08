<?php

namespace App\Filament\Resources\Participacions\Schemas;

use App\Filament\Schemas\DatosVoceroSchema;
use App\Filament\Schemas\UbicacionGeograficaSchema;
use App\Models\AreaItem;
use App\Models\Comuna;
use App\Models\ConsejoComunal;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ParticipacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Planificación')
                    ->schema([
                        DatePicker::make('fecha')
                            ->required(),
                        TextInput::make('localidad')
                            ->default('COMUNIDAD')
                            ->required(),
                        TextInput::make('cantidad_cc')
                            ->label('Cantidad de C.C. participantes')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Select::make('tipos_obpp_id')
                            ->label('Tipo de OBPP')
                            ->relationship('obpp', 'nombre')
                            ->required(),
                        /*TextInput::make('situr_obpp')
                            ->label('Código SITUR de la OBPP')
                            ->required(),*/
                        /*TextInput::make('nombre_obpp')
                            ->label('Nombre de la OBPP')
                            ->required()
                            ->columnSpan(2),*/
                        Select::make('situr_obpp')
                            ->label('Código SITUR de la OBPP')
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $options['Consejos Comunales'] = array_merge(
                                    ConsejoComunal::query()
                                        ->where('situr_nuevo', 'like', "%{$search}%")
                                        ->limit(30)
                                        ->pluck('situr_nuevo', 'situr_nuevo')
                                        ->toArray(),
                                    ConsejoComunal::query()
                                        ->where('situr_viejo', 'like', "%{$search}%")
                                        ->limit(30)
                                        ->pluck('situr_viejo', 'situr_viejo')
                                        ->toArray()
                                );

                                $options['Circuitos o Comunas'] = Comuna::query()
                                    ->where('cod_situr', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('cod_situr', 'cod_situr')
                                    ->toArray();
                                $options['Otro'] = [$search => $search];
                                return $options;
                            })
                            ->getOptionLabelUsing(fn($value): ?string => ConsejoComunal::find($value)?->nombre ?? $value),
                        Select::make('nombre_obpp')
                            ->label('Nombre de la OBPP')
                            ->required()
                            ->columnSpan(2)
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $options['Consejos Comunales'] = ConsejoComunal::query()
                                    ->where('nombre', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('nombre', 'nombre')
                                    ->toArray();
                                $options['Circuitos o Comunas'] = Comuna::query()
                                    ->where('nombre', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('nombre', 'nombre')
                                    ->toArray();
                                $options['Otro'] = [$search => $search];
                                return $options;
                            })
                            ->getOptionLabelUsing(fn($value): ?string => ConsejoComunal::find($value)?->nombre ?? $value),
                        Select::make('tipos_poblacion_id')
                            ->label('Tipo de C.C./Comuna')
                            ->relationship('poblacion', 'nombre')
                            ->required(),
                        Select::make('areas_items_id')
                            ->label('Acompañamiento')
                            ->live()
                            ->required()
                            ->columnSpan(2)
                            ->relationship(
                                'area',
                                'nombre',
                                fn(Builder $query) => $query->whereRelation('area', 'nombre', 'PARTICIPACION')
                            )
                            ->afterStateUpdated(function (Set $set): void {
                                $set('areas_procesos_id', null);
                            })
                            ->getOptionLabelFromRecordUsing(fn(AreaItem $record) => Str::replace('_', ' ', $record->nombre)),
                        Select::make('areas_procesos_id')
                            ->label('Proceso')
                            ->required()
                            ->columnSpan(2)
                            ->relationship(
                                'proceso',
                                'nombre',
                                fn(Builder $query, Get $get) => $query->where('items_id', $get('areas_items_id'))
                            )
                            ->disabled(fn(Get $get): bool => empty($get('areas_items_id'))),
                        Textarea::make('observacion')
                            ->label('Observación')
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->compact()
                    ->collapsible()
                    ->columnSpanFull(),
                DatosVoceroSchema::schema(),
                UbicacionGeograficaSchema::schema(true),
            ]);
    }
}

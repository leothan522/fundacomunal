<?php

namespace App\Filament\Resources\Formacions\Schemas;

use App\Filament\Schemas\DatosObppForm;
use App\Filament\Schemas\DatosVoceroForm;
use App\Filament\Schemas\UbicacionGeograficaForm;
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

class FormacionForm
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
                            ->relationship(
                                'obpp',
                                'nombre',
                                fn(Builder $query) => $query->where('fortalecimiento', 0)
                            )
                            ->required(),
                        DatosObppForm::siturSelect(),
                        DatosObppForm::nombreSelect(),
                        Select::make('tipos_poblacion_id')
                            ->label('Tipo de C.C./Comuna')
                            ->relationship('poblacion', 'nombre')
                            ->required(),
                        Select::make('areas_items_id')
                            ->label('Tipo de proceso formativo (Territorial)')
                            ->live()
                            ->required()
                            ->columnSpan(2)
                            ->relationship(
                                'area',
                                'nombre',
                                fn(Builder $query) => $query->whereRelation('area', 'nombre', 'FORMACION')->withoutTrashed()
                            )
                            ->afterStateUpdated(function (Set $set): void {
                                $set('areas_procesos_id', null);
                            })
                            ->getOptionLabelFromRecordUsing(fn(AreaItem $record) => Str::replace('_', ' ', $record->nombre)),
                        Select::make('areas_procesos_id')
                            ->label('Temática formativa')
                            ->required()
                            ->columnSpan(2)
                            ->relationship(
                                'proceso',
                                'nombre',
                                fn(Builder $query, Get $get) => $query->where('items_id', $get('areas_items_id'))
                            )
                            ->disabled(fn(Get $get): bool => empty($get('areas_items_id'))),
                        Select::make('estrategias_formacion_id')
                            ->label('Estrategia de Fomación')
                            ->relationship('estrategia', 'nombre')
                            ->required()
                            ->columnSpan(2),
                        Select::make('modalidades_formacion_id')
                            ->label('Modalidad')
                            ->relationship('modalidad', 'nombre')
                            ->required()
                            ->columnSpan(2),
                        Textarea::make('observacion')
                            ->label('Observación')
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->compact()
                    ->collapsible()
                    ->columnSpanFull(),
                DatosVoceroForm::schema(),
                UbicacionGeograficaForm::schema(true),
            ]);
    }
}

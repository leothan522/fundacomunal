<?php

namespace App\Filament\Resources\Fortalecimientos\Schemas;

use App\Filament\Schemas\DatosObppForm;
use App\Filament\Schemas\DatosVoceroForm;
use App\Filament\Schemas\UbicacionGeograficaForm;
use App\Models\AreaItem;
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

class FortalecimientoForm
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
                        Select::make('tipos_obpp_id')
                            ->label('Tipo de Organización Socio Productiva (OSP)')
                            ->relationship(
                                'obpp',
                                'nombre',
                                fn(Builder $query) => $query->where('fortalecimiento', 1)
                            )
                            ->columnSpan(2)
                            ->required(),
                        TextInput::make('nombre_osp')
                            ->maxLength(255)
                            ->label('Nombre de la OSP')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('rif_osp')
                            ->maxLength(255)
                            ->required()
                            ->label('RIF'),
                        Select::make('tipos_economicas_id')
                            ->label('Tipo de actividad economica')
                            ->relationship('economica', 'nombre')
                            ->required(),
                        DatosObppForm::siturSelect(),
                        DatosObppForm::nombreSelect(),
                        Select::make('etapas_id')
                            ->label('Etapa del proyecto')
                            ->relationship('etapa', 'nombre')
                            ->required(),
                        Select::make('areas_items_id')
                            ->label('Acompañamiento')
                            ->live()
                            ->required()
                            ->columnSpan(2)
                            ->relationship(
                                'area',
                                'nombre',
                                fn(Builder $query) => $query->whereRelation('area', 'nombre', 'FORTALECIMIENTO')
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
                        Textarea::make('descripcion_proyecto')
                            ->label('Descripción del proyecto')
                            ->required()
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

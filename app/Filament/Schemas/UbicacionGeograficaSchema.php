<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;

class UbicacionGeograficaSchema
{
    public static function schema()
    {
        return Section::make('Ubicación Geográfica')
            ->schema([
                Select::make('redis_id')
                    ->label('REDI')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->relationship('redi', 'nombre')
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        $set('estados_id', null);
                        $set('municipios_id', null);
                    }),
                Select::make('estados_id')
                    ->label('Estado')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        $set('municipios_id', null);
                    })
                    ->relationship(
                        'estado',
                        'nombre',
                        fn(Builder $query, Get $get) => $query->where('redis_id', $get('redis_id'))
                    )
                    ->disabled(fn(Get $get): bool => empty($get('redis_id'))),
                Select::make('municipios_id')
                    ->label('Municipio')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->relationship(
                        'municipio',
                        'nombre',
                        fn(Builder $query, Get $get) => $query->where('estados_id', $get('estados_id'))
                    )
                    ->disabled(fn(Get $get): bool => empty($get('estados_id'))),
                TextInput::make('parroquia')
                    ->required()
                    ->maxLength(255),
            ])
            ->compact()
            ->collapsible();
    }
}

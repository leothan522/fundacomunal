<?php

namespace App\Filament\Schemas;

use App\Models\GestionHumana;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;

class UbicacionGeograficaForm
{
    public static function schema(bool $default = false)
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
                    })
                    ->default(function () use ($default){
                        $response = null;
                        if ($default){
                            $gestionHumana = GestionHumana::where('users_id', auth()->id())->first();
                            $response = $gestionHumana?->redis_id;
                        }
                        return $response;
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
                    ->default(function () use ($default){
                        $response = null;
                        if ($default){
                            $gestionHumana = GestionHumana::where('users_id', auth()->id())->first();
                            $response = $gestionHumana?->estados_id;
                        }
                        return $response;
                    })
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
                    ->default(function () use ($default){
                        $response = null;
                        if ($default && !isAdmin()){
                            $gestionHumana = GestionHumana::where('users_id', auth()->id())->first();
                            $response = $gestionHumana?->municipios_id;
                        }
                        return $response;
                    })
                    ->disabled(fn(Get $get): bool => empty($get('estados_id'))),
                TextInput::make('parroquia')
                    ->required()
                    ->maxLength(255),
            ])
            ->compact()
            ->collapsible();
    }
}

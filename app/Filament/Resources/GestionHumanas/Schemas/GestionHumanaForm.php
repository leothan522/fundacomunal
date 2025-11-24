<?php

namespace App\Filament\Resources\GestionHumanas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class GestionHumanaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos Básicos')
                    ->schema([
                        TextInput::make('cedula')
                            ->label('Cédula')
                            ->required()
                            ->numeric(),
                        TextInput::make('nombre')
                            ->required(),
                        TextInput::make('apellido')
                            ->required(),
                        TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel()
                            ->required(),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email(),
                    ])
                    ->compact()
                    ->collapsible()
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Datos Laborales')
                    ->schema([
                        Select::make('tipos_personal_id')
                            ->label('Tipo Personal')
                            ->relationship('tipoPersonal', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('categorias_id')
                            ->label('Categoria')
                            ->relationship('categoria', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('ente')
                            ->label('Órgano o Ente Adscrito')
                            ->default('FUNDACOMUNAL')
                            ->required(),
                        Textarea::make('observacion')
                            ->label('Observación')
                            ->columnSpanFull(),
                    ])
                    ->compact()
                    ->collapsible(),
                Section::make('Ubicación Geográfica')
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
                        TextInput::make('parroquia'),
                    ])
                    ->compact()
                    ->collapsible(),
            ]);
    }
}

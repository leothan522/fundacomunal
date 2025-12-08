<?php

namespace App\Filament\Resources\GestionHumanas\Schemas;

use App\Filament\Schemas\UbicacionGeograficaForm;
use Filament\Forms\Components\DatePicker;
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
                            ->unique()
                            ->required()
                            ->numeric()
                            ->minLength(6)
                            ->maxLength(8),
                        TextInput::make('nombre')
                            ->required(),
                        TextInput::make('apellido')
                            ->required(),
                        TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel()
                            ->telRegex('/^[0-9]{4}-[0-9]{7}$/')
                            ->required(),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->unique(),
                        DatePicker::make('fecha_nacimiento'),
                    ])
                    ->compact()
                    ->collapsible()
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Datos Laborales')
                    ->schema([
                        Select::make('tipos_personal_id')
                            ->label('Labor que Ejerce')
                            ->relationship('tipoPersonal', 'nombre')
                            ->required(),
                        Select::make('categorias_id')
                            ->label('Categoria')
                            ->relationship('categoria', 'nombre')
                            ->required(),
                        TextInput::make('ente')
                            ->label('Órgano o Ente Adscrito')
                            ->default('FUNDACOMUNAL')
                            ->required(),
                        DatePicker::make('fecha_ingreso'),
                        Textarea::make('observacion')
                            ->label('Observación')
                            ->columnSpanFull(),
                    ])
                    ->compact()
                    ->collapsible(),
                UbicacionGeograficaForm::schema(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\GestionHumanas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GestionHumanaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cedula')
                    ->required()
                    ->numeric(),
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('apellido')
                    ->required(),
                TextInput::make('telefono')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('tipos_personal_id')
                    ->numeric(),
                TextInput::make('categorias_id')
                    ->numeric(),
                TextInput::make('ente'),
                TextInput::make('redis_id')
                    ->numeric(),
                TextInput::make('estados_id')
                    ->numeric(),
                TextInput::make('municipios_id')
                    ->numeric(),
                TextInput::make('parroquia'),
                Textarea::make('observacion')
                    ->columnSpanFull(),
                DatePicker::make('fecha'),
                TextInput::make('users_id')
                    ->numeric(),
            ]);
    }
}

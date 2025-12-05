<?php

namespace App\Filament\Resources\ConsejoComunals\Schemas;

use App\Filament\Schemas\UbicacionGeograficaSchema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ConsejoComunalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos Básicos')
                    ->schema([
                        TextInput::make('nombre')
                            ->required(),
                        TextInput::make('situr_viejo')
                            ->unique(),
                        TextInput::make('situr_nuevo')
                            ->unique(),
                        Select::make('tipos_poblacion_id')
                            ->relationship('tipo', 'nombre')
                            ->required(),
                        Select::make('comunas_id')
                            ->relationship('comuna', 'nombre')
                            ->preload()
                            ->searchable(['nombre', 'cod_com', 'cod_situr']),
                    ])
                    ->compact()
                    ->collapsible(),
                UbicacionGeograficaSchema::schema(),
            ]);
    }
}

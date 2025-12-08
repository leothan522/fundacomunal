<?php

namespace App\Filament\Resources\Comunas\Schemas;

use App\Filament\Schemas\UbicacionGeograficaForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ComunaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos Básicos')
                    ->schema([
                        TextInput::make('nombre')
                            ->unique()
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('cod_com')
                            ->label('COD. COM')
                            ->unique()
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('cod_situr')
                            ->label('COD. SITUR')
                            ->unique()
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('cantidad_cc')
                            ->label('Cantidad C.C.')
                            ->required()
                            ->numeric(),
                    ])
                    ->compact()
                    ->collapsible(),
                UbicacionGeograficaForm::schema(),
            ]);
    }
}

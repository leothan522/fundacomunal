<?php

namespace App\Filament\Schemas;

use App\Models\GestionHumana;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;

class DatosVoceroSchema
{
    public static function schema()
    {
        return Section::make('Datos del Vocero')
            ->schema([
                TextInput::make('vocero_nombre')
                    ->label('Nombre y Apllido')
                    ->required(),
                TextInput::make('vocero_telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->telRegex('/^[0-9]{4}-[0-9]{7}$/')
                    ->required(),
                Select::make('gestion_humana_id')
                    ->label('Promotor')
                    ->required()
                    ->live()
                    ->relationship(
                        'promotor',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('tipoPersonal', 'nombre', 'PROMOTORES')->orderBy('nombre')
                    )
                    ->searchable(['nombre', 'apellido'])
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn(GestionHumana $record) => strtok($record->nombre, " ") . " " . strtok($record->apellido, " "))
                    ->default(function () {
                        $response = null;
                        if (!isAdmin()) {
                            $promotor = GestionHumana::where('users_id', auth()->id())->first();
                            $response = $promotor?->id;
                        }
                        return $response;
                    })
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        if (isAdmin()) {
                            $gestionHumana = GestionHumana::find($get('gestion_humana_id'));
                            if ($gestionHumana) {
                                $set('redis_id', $gestionHumana->redis_id);
                                $set('estados_id', $gestionHumana->estados_id);
                                $set('municipios_id', $gestionHumana->municipios_id);
                                $set('parroquia', $gestionHumana->parroquia);
                            }
                        }
                    }),
            ])
            ->compact()
            ->collapsible();
    }
}

<?php

namespace App\Filament\Resources\Biens\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BienForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Principal')
                    ->schema([
                        self::formSelect('bienes_tipos_id', 'Tipo / Categoría', 'tipo', true),
                        TextInput::make('serial')
                            ->label('Serial / Identificador')
                            ->required()
                            ->unique(ignoreRecord: true),
                        self::formSelect('bienes_ubicaciones_id', 'Ubicación', 'ubicacion', true),
                        self::formSelect('bienes_condiciones_id', 'Condición', 'condicion', true),
                    ])
                    ->compact(),
                Section::make('Atributos')
                    ->schema([
                        self::formSelect('bienes_marcas_id', 'Marca', 'marca'),
                        self::formSelect('bienes_modelos_id', 'Modelo', 'modelo'),
                        self::formSelect('bienes_colores_id', 'Color', 'color'),
                        Textarea::make('informacion_adicional')
                            ->label('Información Adicional'),
                    ])
                    ->compact(),
            ]);
    }

    public static function formSelect($make, $label, $name, bool $required = false)
    {
        return Select::make($make)
            ->label($label)
            ->relationship(
                name: $name,
                titleAttribute: 'nombre',
                modifyQueryUsing: fn(Builder $query) => $query->where('activo', true)->orderBy('nombre')
            )
            ->getOptionLabelFromRecordUsing(fn($record) => Str::upper($record->nombre))
            ->createOptionForm([
                TextInput::make('nombre')
                    ->label($label)
                    ->unique()
                    ->required(),
            ])
            ->createOptionAction(fn(Action $action) => $action->modalWidth(Width::ExtraSmall))
            ->searchable()
            ->preload()
            ->required($required);
    }
}

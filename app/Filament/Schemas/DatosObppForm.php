<?php

namespace App\Filament\Schemas;

use App\Models\Comuna;
use App\Models\ConsejoComunal;
use Filament\Forms\Components\Select;

class DatosObppForm
{
    public static function siturSelect()
    {
        return Select::make('situr_obpp')
            ->label('Código SITUR de la OBPP')
            ->required()
            ->searchable()
            ->getSearchResultsUsing(function (string $search) {
                $options['Consejos Comunales'] = array_merge(
                    ConsejoComunal::query()
                        ->where('situr_nuevo', 'like', "%{$search}%")
                        ->limit(30)
                        ->pluck('situr_nuevo', 'situr_nuevo')
                        ->toArray(),
                    ConsejoComunal::query()
                        ->where('situr_viejo', 'like', "%{$search}%")
                        ->limit(30)
                        ->pluck('situr_viejo', 'situr_viejo')
                        ->toArray()
                );

                $options['Circuitos o Comunas'] = Comuna::query()
                    ->where('cod_situr', 'like', "%{$search}%")
                    ->limit(50)
                    ->pluck('cod_situr', 'cod_situr')
                    ->toArray();
                $options['Otro'] = [$search => $search];
                return $options;
            })
            ->getOptionLabelUsing(fn($value): ?string => ConsejoComunal::find($value)?->nombre ?? $value);
    }

    public static function nombreSelect()
    {
        return Select::make('nombre_obpp')
            ->label('Nombre de la OBPP')
            ->required()
            ->columnSpan(2)
            ->searchable()
            ->getSearchResultsUsing(function (string $search) {
                $options['Consejos Comunales'] = ConsejoComunal::query()
                    ->where('nombre', 'like', "%{$search}%")
                    ->limit(50)
                    ->pluck('nombre', 'nombre')
                    ->toArray();
                $options['Circuitos o Comunas'] = Comuna::query()
                    ->where('nombre', 'like', "%{$search}%")
                    ->limit(50)
                    ->pluck('nombre', 'nombre')
                    ->toArray();
                $options['Otro'] = [$search => $search];
                return $options;
            })
            ->getOptionLabelUsing(fn($value): ?string => ConsejoComunal::find($value)?->nombre ?? $value);
    }
}

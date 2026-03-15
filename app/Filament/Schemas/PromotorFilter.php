<?php

namespace App\Filament\Schemas;

use App\Models\GestionHumana;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PromotorFilter
{
    public static function schema()
    {
        return SelectFilter::make('promotor')
            ->relationship(
                'promotor',
                'id',
                fn(Builder $query) => $query->whereRelation('tipoPersonal', 'nombre', 'PROMOTORES')->orderBy('nombre')
            )
            ->getOptionLabelFromRecordUsing(fn(GestionHumana $record) => Str::upper($record->short_name))
            ->indicateUsing(function (array $data): ?string {
                if (! $data['value']){
                    return null;
                }
                $record = GestionHumana::find($data['value']);
                return 'Promotor: '.Str::upper($record->short_name);
            })
            ->searchable(['nombre', 'apellido'])
            ->preload();
    }
}

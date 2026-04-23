<?php

namespace App\Filament\Resources\EleccionSemanals;

use App\Filament\Resources\EleccionSemanals\Pages\CreateEleccionSemanal;
use App\Filament\Resources\EleccionSemanals\Pages\EditEleccionSemanal;
use App\Filament\Resources\EleccionSemanals\Pages\ListEleccionSemanals;
use App\Filament\Resources\EleccionSemanals\Pages\ViewEleccionSemanal;
use App\Filament\Resources\EleccionSemanals\Schemas\EleccionSemanalForm;
use App\Filament\Resources\EleccionSemanals\Schemas\EleccionSemanalInfolist;
use App\Filament\Resources\EleccionSemanals\Tables\EleccionSemanalsTable;
use App\Models\EleccionSemanal;
use App\Models\Participacion;
use BackedEnum;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class EleccionSemanalResource extends Resource
{
    protected static ?string $model = Participacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string | UnitEnum | null $navigationGroup = 'Planificación Semanal';

    protected static ?int $navigationSort = 71;

    protected static ?string $modelLabel = 'Elecciones';

    protected static ?string $recordTitleAttribute = 'situr_obpp';

    public static function form(Schema $schema): Schema
    {
        return EleccionSemanalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EleccionSemanalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EleccionSemanalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEleccionSemanals::route('/'),
            'view' => ViewEleccionSemanal::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Ahora solo filtramos por el proceso, sin restringir la fecha aquí
        return parent::getEloquentQuery()
            ->with(['promotor', 'municipio', 'proceso'])
            ->whereHas('proceso', function ($query) {
                $query->where('nombre', 'LIKE', '%RENOVACION DE VOCERIAS%');
            });
    }
}

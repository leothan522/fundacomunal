<?php

namespace App\Filament\Resources\Formacions;

use App\Filament\Resources\Formacions\Pages\CreateFormacion;
use App\Filament\Resources\Formacions\Pages\EditFormacion;
use App\Filament\Resources\Formacions\Pages\ListFormacions;
use App\Filament\Resources\Formacions\Schemas\FormacionForm;
use App\Filament\Resources\Formacions\Schemas\FormacionInfoList;
use App\Filament\Resources\Formacions\Tables\FormacionsTable;
use App\Models\Formacion;
use App\Traits\ValidarRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class FormacionResource extends Resource
{
    use ValidarRecord;

    protected static ?string $model = Formacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;
    protected static string | UnitEnum | null $navigationGroup = 'Áreas Sustantivas';
    protected static ?string $modelLabel = 'Formación';
    protected static ?string $pluralLabel = 'Formación';
    protected static ?string $slug = 'formacion';
    protected static ?int $navigationSort = 50;

    protected static ?string $recordTitleAttribute = 'nombre_obpp';

    protected static ?int $globalSearchSort = 3;

    protected static int $globalSearchResultsLimit = 10;

    public static function getGloballySearchableAttributes(): array
    {
        return ['situr_obpp', 'nombre_obpp', 'promotor.nombre', 'promotor.apellido'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'OBPP' => $record->obpp->nombre,
            'Proceso' => $record->proceso->nombre,
            'Fecha' => getFecha($record->fecha),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->orderByDesc('fecha');
    }

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return self::getUrl('index', [
            'tableAction' => 'view', // Nombre de la acción en tu método table()
            'tableActionRecord' => $record->getKey(), // El ID del registro
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return FormacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormacionsTable::configure($table);
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
            'index' => ListFormacions::route('/'),
            'create' => CreateFormacion::route('/create'),
            'edit' => EditFormacion::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FormacionInfoList::configure($schema);
    }

}

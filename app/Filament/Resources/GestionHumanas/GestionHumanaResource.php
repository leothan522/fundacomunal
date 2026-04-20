<?php

namespace App\Filament\Resources\GestionHumanas;

use App\Filament\Resources\GestionHumanas\Pages\CreateGestionHumana;
use App\Filament\Resources\GestionHumanas\Pages\EditGestionHumana;
use App\Filament\Resources\GestionHumanas\Pages\ListGestionHumanas;
use App\Filament\Resources\GestionHumanas\Schemas\GestionHumanaForm;
use App\Filament\Resources\GestionHumanas\Schemas\GestionHumanaInfoList;
use App\Filament\Resources\GestionHumanas\Tables\GestionHumanasTable;
use App\Models\GestionHumana;
use App\Traits\ValidarRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use UnitEnum;

class GestionHumanaResource extends Resource
{
    use ValidarRecord;

    protected static ?string $model = GestionHumana::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string|UnitEnum|null $navigationGroup = 'Gestión Humana';
    protected static ?string $slug = 'gestion-humana';
    protected static ?string $modelLabel = 'Gestión Humana';
    protected static ?string $pluralModelLabel = 'Gestión Humana';

    protected static ?string $recordTitleAttribute = 'cedula';

    protected static ?int $globalSearchSort = 1;

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return Str::upper($record->short_name);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['cedula', 'nombre', 'apellido'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Cédula' => formatoMillares($record->cedula, 0),
            'Labor' => $record->tipoPersonal->nombre,
        ];
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
        return GestionHumanaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GestionHumanasTable::configure($table);
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
            'index' => ListGestionHumanas::route('/'),
            'create' => CreateGestionHumana::route('/create'),
            'edit' => EditGestionHumana::route('/{record}/edit'),
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
        return GestionHumanaInfoList::configure($schema);
    }

}

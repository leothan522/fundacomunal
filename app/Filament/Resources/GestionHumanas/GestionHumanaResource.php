<?php

namespace App\Filament\Resources\GestionHumanas;

use App\Filament\Resources\GestionHumanas\Pages\CreateGestionHumana;
use App\Filament\Resources\GestionHumanas\Pages\EditGestionHumana;
use App\Filament\Resources\GestionHumanas\Pages\ListGestionHumanas;
use App\Filament\Resources\GestionHumanas\Schemas\GestionHumanaForm;
use App\Filament\Resources\GestionHumanas\Tables\GestionHumanasTable;
use App\Models\GestionHumana;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class GestionHumanaResource extends Resource
{
    protected static ?string $model = GestionHumana::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string | UnitEnum | null $navigationGroup = 'Áreas Sustantivas';
    protected static ?string $slug = 'gestion-humana';
    protected static ?string $modelLabel = 'Gestión Humana';
    protected static ?string $pluralModelLabel = 'Gestión Humana';

    protected static ?string $recordTitleAttribute = 'cedula';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->nombre.' '.$record->apellido;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['cedula', 'nombre', 'apellido'];
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
}

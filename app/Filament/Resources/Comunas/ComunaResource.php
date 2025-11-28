<?php

namespace App\Filament\Resources\Comunas;

use App\Filament\Resources\Comunas\Pages\CreateComuna;
use App\Filament\Resources\Comunas\Pages\EditComuna;
use App\Filament\Resources\Comunas\Pages\ListComunas;
use App\Filament\Resources\Comunas\Schemas\ComunaForm;
use App\Filament\Resources\Comunas\Tables\ComunasTable;
use App\Models\Comuna;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ComunaResource extends Resource
{
    protected static ?string $model = Comuna::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | UnitEnum | null $navigationGroup = 'OBPP';
    protected static ?int $navigationSort = 80;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function getGloballySearchableAttributes(): array
    {
        return ['nombre', 'cod_com', 'cod_situr'];
    }

    public static function form(Schema $schema): Schema
    {
        return ComunaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComunasTable::configure($table);
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
            'index' => ListComunas::route('/'),
            'create' => CreateComuna::route('/create'),
            'edit' => EditComuna::route('/{record}/edit'),
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

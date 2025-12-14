<?php

namespace App\Filament\Resources\Fortalecimientos;

use App\Filament\Resources\Fortalecimientos\Pages\CreateFortalecimiento;
use App\Filament\Resources\Fortalecimientos\Pages\EditFortalecimiento;
use App\Filament\Resources\Fortalecimientos\Pages\ListFortalecimientos;
use App\Filament\Resources\Fortalecimientos\Schemas\FortalecimientoForm;
use App\Filament\Resources\Fortalecimientos\Schemas\FortalecimientoInfolist;
use App\Filament\Resources\Fortalecimientos\Tables\FortalecimientosTable;
use App\Models\Fortalecimiento;
use App\Traits\ValidarRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class FortalecimientoResource extends Resource
{
    use ValidarRecord;

    protected static ?string $model = Fortalecimiento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;
    protected static string | UnitEnum | null $navigationGroup = 'Áreas Sustantivas';
    protected static ?string $modelLabel = 'fortalecimiento';
    protected static ?string $pluralLabel = 'fortalecimiento';
    protected static ?string $slug = 'fortalecimiento';
    protected static ?int $navigationSort = 55;
    protected static ?string $recordTitleAttribute = 'nombre_obpp';

    public static function form(Schema $schema): Schema
    {
        return FortalecimientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FortalecimientosTable::configure($table);
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
            'index' => ListFortalecimientos::route('/'),
            'create' => CreateFortalecimiento::route('/create'),
            'edit' => EditFortalecimiento::route('/{record}/edit'),
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
        return FortalecimientoInfolist::configure($schema);
    }

}

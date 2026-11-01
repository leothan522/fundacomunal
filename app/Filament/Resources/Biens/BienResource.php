<?php

namespace App\Filament\Resources\Biens;

use App\Filament\Resources\Biens\Pages\CreateBien;
use App\Filament\Resources\Biens\Pages\EditBien;
use App\Filament\Resources\Biens\Pages\ListBiens;
use App\Filament\Resources\Biens\Schemas\BienForm;
use App\Filament\Resources\Biens\Schemas\BienInfoList;
use App\Filament\Resources\Biens\Tables\BiensTable;
use App\Models\Bien;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BienResource extends Resource
{
    protected static ?string $model = Bien::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;
    protected static ?string $navigationLabel = "Bienes";
    protected static ?string $pluralModelLabel = "Bienes";
    protected static ?string $slug = 'bienes';

    protected static ?string $recordTitleAttribute = 'serial';

    public static function form(Schema $schema): Schema
    {
        return BienForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BiensTable::configure($table);
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
            'index' => ListBiens::route('/'),
            'create' => CreateBien::route('/create'),
            'edit' => EditBien::route('/{record}/edit'),
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
        return BienInfoList::configure($schema);
    }

}

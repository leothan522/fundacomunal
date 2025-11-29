<?php

namespace App\Filament\Resources\Comunas;

use App\Filament\Resources\Comunas\Pages\CreateComuna;
use App\Filament\Resources\Comunas\Pages\EditComuna;
use App\Filament\Resources\Comunas\Pages\ListComunas;
use App\Filament\Resources\Comunas\RelationManagers\ConsejosRelationManager;
use App\Filament\Resources\Comunas\Schemas\ComunaForm;
use App\Filament\Resources\Comunas\Tables\ComunasTable;
use App\Filament\Schemas\UbicacionGeograficaFieldset;
use App\Models\Comuna;
use BackedEnum;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ComunaResource extends Resource
{
    protected static ?string $model = Comuna::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'OBPP';
    protected static ?int $navigationSort = 80;
    protected static ?string $navigationLabel = 'Cirtuitos o Comunas';

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
            ConsejosRelationManager::class
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

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Datos Básicos')
                    ->schema([
                        TextEntry::make('nombre')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('cod_com')
                            ->label('COD. COM')
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('cod_situr')
                            ->label('COD. SITUR')
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('cantidad_cc')
                            ->formatStateUsing(fn(Comuna $record): int => $record->consejos->count())
                            ->numeric()
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                    ])
                    ->columns(1),
                UbicacionGeograficaFieldset::schema(),
                Section::make('Consejos Comunales')
                    ->schema([
                        RepeatableEntry::make('consejos')
                            ->schema([
                                TextEntry::make('nombre')
                                    ->wrap()
                                    ->bulleted()
                                    ->hiddenLabel()
                                    ->color('primary')
                                    ->size(TextSize::Medium)
                                    ->weight(FontWeight::Bold)
                                    ->copyable()
                            ])
                            ->contained(false)
                            ->hiddenLabel()
                    ])
                    ->compact()
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
            ]);
    }

}

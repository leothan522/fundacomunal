<?php

namespace App\Filament\Resources\ConsejoComunals;

use App\Filament\Resources\ConsejoComunals\Pages\CreateConsejoComunal;
use App\Filament\Resources\ConsejoComunals\Pages\EditConsejoComunal;
use App\Filament\Resources\ConsejoComunals\Pages\ListConsejoComunals;
use App\Filament\Resources\ConsejoComunals\Schemas\ConsejoComunalForm;
use App\Filament\Resources\ConsejoComunals\Tables\ConsejoComunalsTable;
use App\Filament\Schemas\UbicacionGeograficaFieldset;
use App\Models\ConsejoComunal;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use UnitEnum;

class ConsejoComunalResource extends Resource
{
    protected static ?string $model = ConsejoComunal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'OBPP';
    protected static ?int $navigationSort = 81;
    protected static ?string $modelLabel = 'Consejo Comunal';
    protected static ?string $pluralModelLabel = 'Consejos Comunales';
    protected static ?string $slug = 'consejos-comunales';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function getGloballySearchableAttributes(): array
    {
        return ['nombre', 'situr_viejo', 'situr_nuevo'];
    }

    public static function form(Schema $schema): Schema
    {
        return ConsejoComunalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsejoComunalsTable::configure($table);
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
            'index' => ListConsejoComunals::route('/'),
            'create' => CreateConsejoComunal::route('/create'),
            'edit' => EditConsejoComunal::route('/{record}/edit'),
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
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('situr_viejo')
                            ->label('SITUR Viejo')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('situr_nuevo')
                            ->label('SITUR Nuevo')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('tipo.nombre')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('fecha_asamblea')
                            ->date()
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state): bool => empty($state)),
                        TextEntry::make('fecha_vencimiento')
                            ->date()
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state): bool => empty($state)),
                        TextEntry::make('comuna.nombre')
                            ->label('Circuito o Comuna')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn(?string $state): bool => empty($state)),
                    ])
                    ->columns(1),
                UbicacionGeograficaFieldset::schema(),
            ]);
    }

}

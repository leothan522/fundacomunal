<?php

namespace App\Filament\Resources\GestionHumanas;

use App\Filament\Resources\GestionHumanas\Pages\CreateGestionHumana;
use App\Filament\Resources\GestionHumanas\Pages\EditGestionHumana;
use App\Filament\Resources\GestionHumanas\Pages\ListGestionHumanas;
use App\Filament\Resources\GestionHumanas\Pages\ViewGestionHumana;
use App\Filament\Resources\GestionHumanas\Schemas\GestionHumanaForm;
use App\Filament\Resources\GestionHumanas\Tables\GestionHumanasTable;
use App\Models\GestionHumana;
use BackedEnum;
use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
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
    protected static ?string $model = GestionHumana::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string|UnitEnum|null $navigationGroup = 'Áreas Sustantivas';
    protected static ?string $slug = 'gestion-humana';
    protected static ?string $modelLabel = 'Gestión Humana';
    protected static ?string $pluralModelLabel = 'Gestión Humana';

    protected static ?string $recordTitleAttribute = 'cedula';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->nombre . ' ' . $record->apellido;
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

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Datos Básicos')
                    ->schema([
                        TextEntry::make('cedula')
                            ->label('Cédula')
                            ->numeric()
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('nombre')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('apellido')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('telefono')
                            ->label('Teléfono')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('email')
                            ->formatStateUsing(fn(string $state): string => Str::lower($state))
                            ->label(__('Email'))
                            ->default('-')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->columnSpan(2),
                        TextEntry::make('fecha_nacimiento')
                            ->date()
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn(GestionHumana $record): bool => empty($record->fecha_nacimiento)),
                        TextEntry::make('edad')
                            ->state(fn(GestionHumana $record): ?string => $record->fecha_nacimiento ? Carbon::parse($record->fecha_nacimiento)->age . ' años' : null)
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn(GestionHumana $record): bool => empty($record->fecha_nacimiento)),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Fieldset::make('Datos Laborales')
                    ->schema([
                        TextEntry::make('tipoPersonal.nombre')
                            ->label('Tipo Personal')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('categoria.nombre')
                            ->label('Categoria')
                            ->default('-')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('ente')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->label('Ente Adscrito')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('fecha_ingreso')
                            ->date()
                            ->inlineLabel()
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn(GestionHumana $record): bool => empty($record->fecha_ingreso)),
                        TextEntry::make('observacion')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->label('Observación')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable()
                            ->hidden(fn($state): bool => empty($state)),
                    ])
                    ->columns(1),
                Fieldset::make('Ubicación Geográfica')
                    ->schema([
                        TextEntry::make('redi.nombre')
                            ->label('REDI')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('estado.nombre')
                            ->label('Estado')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('municipio.nombre')
                            ->label('Municipio')
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                        TextEntry::make('parroquia')
                            ->formatStateUsing(fn(string $state): string => Str::upper($state))
                            ->inlineLabel()
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->copyable(),
                    ])
                    ->columns(1)
            ]);
    }

}

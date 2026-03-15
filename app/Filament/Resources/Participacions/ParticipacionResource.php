<?php

namespace App\Filament\Resources\Participacions;

use App\Filament\Resources\Participacions\Pages\CreateParticipacion;
use App\Filament\Resources\Participacions\Pages\EditParticipacion;
use App\Filament\Resources\Participacions\Pages\ListParticipacions;
use App\Filament\Resources\Participacions\Schemas\ParticipacionForm;
use App\Filament\Resources\Participacions\Schemas\ParticipacionInfoList;
use App\Filament\Resources\Participacions\Tables\ParticipacionsTable;
use App\Models\Participacion;
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

class ParticipacionResource extends Resource
{
    use ValidarRecord;

    protected static ?string $model = Participacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static string|UnitEnum|null $navigationGroup = 'Áreas Sustantivas';
    protected static ?string $modelLabel = 'Participación';
    protected static ?string $pluralModelLabel = 'Participación';
    protected static ?string $slug = 'participacion';

    protected static ?string $recordTitleAttribute = 'nombre_obpp';

    protected static ?int $globalSearchSort = 2;

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
        return ParticipacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParticipacionsTable::configure($table);
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
            'index' => ListParticipacions::route('/'),
            'create' => CreateParticipacion::route('/create'),
            'edit' => EditParticipacion::route('/{record}/edit'),
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
        return ParticipacionInfoList::configure($schema);
    }

}

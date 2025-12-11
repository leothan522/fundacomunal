<?php

namespace App\Filament\Resources\Participacions;

use App\Filament\Resources\Participacions\Pages\CreateParticipacion;
use App\Filament\Resources\Participacions\Pages\EditParticipacion;
use App\Filament\Resources\Participacions\Pages\ListParticipacions;
use App\Filament\Resources\Participacions\Schemas\ParticipacionForm;
use App\Filament\Resources\Participacions\Schemas\ParticipacionInfoList;
use App\Filament\Resources\Participacions\Tables\ParticipacionsTable;
use App\Models\Participacion;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Authorization\DenyResponse;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ParticipacionResource extends Resource
{
    protected static ?string $model = Participacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static string|UnitEnum|null $navigationGroup = 'Áreas Sustantivas';
    protected static ?string $modelLabel = 'Participación';
    protected static ?string $pluralModelLabel = 'Participación';
    protected static ?string $slug = 'participacion';

    protected static ?string $recordTitleAttribute = 'nombre_obpp';

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

    public static function getViewAuthorizationResponse(?Model $record): Response
    {
        if (!$record){
            noDisponibleNotification();
            return DenyResponse::deny('El registro no existe o fue eliminado.');
        }
        return parent::getViewAuthorizationResponse($record);
    }

}

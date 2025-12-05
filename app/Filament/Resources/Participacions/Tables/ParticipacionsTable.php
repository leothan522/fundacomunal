<?php

namespace App\Filament\Resources\Participacions\Tables;

use App\Models\AreaItem;
use App\Models\GestionHumana;
use App\Models\Participacion;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ParticipacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (!isAdmin()) {
                    $query->where(function (Builder $subQuery) {
                        $subQuery->whereRelation('promotor', 'users_id', auth()->id())
                            ->orWhere('users_id', auth()->id());
                    });

                }
                return $query->orderByDesc('fecha');
            })
            ->columns([
                TextColumn::make('fecha_movil')
                    ->label('Fecha')
                    ->default(fn(Participacion $record) => $record->fecha)
                    ->description(fn(Participacion $query) => Str::upper($query->nombre_obpp))
                    ->date()
                    ->wrap()
                    ->hiddenFrom('md'),
                TextColumn::make('fecha')
                    ->date()
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('obpp.nombre')
                    ->label('Tipo OBPP')
                    ->formatStateUsing(fn($state) => Str::upper($state))
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('situr_obpp')
                    ->label('Código SITUR')
                    ->formatStateUsing(fn($state) => Str::upper($state))
                    ->searchable()
                    ->alignCenter()
                    ->grow(false)
                    ->visibleFrom('md'),
                TextColumn::make('nombre_obpp')
                    ->label('Nombre de la OBPP')
                    ->formatStateUsing(fn($state) => Str::upper($state))
                    ->searchable()
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('promotor.nombre')
                    ->formatStateUsing(fn(Participacion $record) => strtok($record->promotor->nombre, " ") . " " . strtok($record->promotor->apellido, " "))
                    ->wrap()
                    ->visibleFrom('md'),
                IconColumn::make('estatus')
                    ->default('-')
                    ->icon(fn(string $state): Heroicon => match ($state) {
                        '0' => Heroicon::OutlinedBackspace,
                        '1' => Heroicon::OutlinedCheckCircle,
                        default => Heroicon::OutlinedClock,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'info',
                        '1' => 'success',
                        default => 'gray',
                    })
                    ->alignCenter()
                    ->grow(false),
            ])
            ->filters([
                SelectFilter::make('municipio')
                    ->relationship(
                        'municipio',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('estado', 'nombre', 'GUÁRICO')
                    )
                    ->searchable()
                    ->preload(),
                SelectFilter::make('promotor')
                    ->relationship(
                        'promotor',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('tipoPersonal', 'nombre', 'PROMOTORES')->orderBy('nombre')
                    )
                    ->getOptionLabelFromRecordUsing(fn(GestionHumana $record) => strtok($record->nombre, " ") . " " . strtok($record->apellido, " "))
                    ->searchable(['nombre', 'apellido'])
                    ->preload(),
                SelectFilter::make('obpp')
                    ->label('Tipo OBPP')
                    ->relationship('obpp', 'nombre'),
                SelectFilter::make('area')
                    ->label('Acompañamiento')
                    ->relationship(
                        'area',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('area', 'nombre', 'PARTICIPACION')
                    )
                    ->getOptionLabelFromRecordUsing(fn(AreaItem $record) => Str::replace('_', ' ', $record->nombre)),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->extraModalFooterActions(fn(): array => [
                            EditAction::make()
                                ->disabled(fn(Participacion $record): bool => !is_null($record->estatus))
                        ]),
                    Action::make('reportar')
                        ->label('Reporte de Actividad')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->schema([
                            TextInput::make('cantidad_familias')
                                ->label('Cantidad de familias beneficiadas')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                            TextInput::make('cantidad_asistentes')
                                ->label('Cantidad de personas asistentes ')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                        ])
                        ->action(function (array $data, Participacion $record): void {
                            $record->cantidad_familias = $data['cantidad_familias'];
                            $record->cantidad_asistentes = $data['cantidad_asistentes'];
                            $record->estatus = 1;
                            $record->save();
                        })
                        ->modalIcon(Heroicon::OutlinedCheckCircle)
                        ->modalWidth(Width::Small)
                        ->hidden(fn(Participacion $record): bool => !is_null($record->estatus)),
                    Action::make('no_realizada')
                        ->label('No Realizada')
                        ->icon(Heroicon::OutlinedBackspace)
                        ->requiresConfirmation()
                        ->color('info')
                        ->action(function (Participacion $record): void {
                            $record->estatus = 0;
                            $record->save();
                        })
                        ->modalIcon(Heroicon::OutlinedBackspace)
                        ->hidden(fn(Participacion $record): bool => !is_null($record->estatus)),
                    Action::make('reset_actividad')
                        ->label('Reset Actividad')
                        ->requiresConfirmation()
                        ->icon(Heroicon::OutlinedClock)
                        ->action(function (Participacion $record): void {
                            $record->cantidad_familias = null;
                            $record->cantidad_asistentes = null;
                            $record->estatus = null;
                            $record->save();
                        })
                        ->modalIcon(Heroicon::OutlinedClock)
                        ->hidden(fn(Participacion $record): bool => is_null($record->estatus) || !isAdmin()),
                    EditAction::make()
                        ->disabled(fn(Participacion $record): bool => !is_null($record->estatus)),
                    DeleteAction::make()
                        ->disabled(fn(Participacion $record): bool => !is_null($record->estatus)),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton(),
            ])
            ->recordUrl(null);
    }
}

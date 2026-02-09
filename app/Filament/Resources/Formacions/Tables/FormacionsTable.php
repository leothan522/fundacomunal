<?php

namespace App\Filament\Resources\Formacions\Tables;

use App\Filament\Schemas\FechaFilter;
use App\Models\AreaItem;
use App\Models\Formacion;
use App\Models\GestionHumana;
use App\Models\MedioVerificacion;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class FormacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (!isAdmin() && !auth()->user()->hasRole('GESTION HUMANA') && !auth()->user()->hasPermissionTo('jefe_area')) {
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
                    ->default(fn(Formacion $record) => $record->fecha)
                    ->description(fn(Formacion $record) => Str::upper($record->estrategia->nombre.' - '.$record->nombre_obpp))
                    ->date()
                    ->wrap()
                    ->hiddenFrom('md'),
                TextColumn::make('fecha')
                    ->date()
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('estrategia.nombre')
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
                    ->formatStateUsing(fn(Formacion $record) => strtok($record->promotor->nombre, " ") . " " . strtok($record->promotor->apellido, " "))
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
                FechaFilter::filter(),
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
                SelectFilter::make('area')
                    ->label('Tipo de Proceso')
                    ->relationship(
                        'area',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('area', 'nombre', 'FORMACION')
                    )
                    ->getOptionLabelFromRecordUsing(fn(AreaItem $record) => Str::replace('_', ' ', $record->nombre)),
                SelectFilter::make('estrategia')
                    ->label('Estrategia')
                    ->relationship('estrategia', 'nombre'),
                SelectFilter::make('modalidad')
                    ->label('Modalidad')
                    ->relationship('estrategia', 'nombre'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->extraModalFooterActions(fn(): array => [
                            EditAction::make()
                                ->disabled(fn(Formacion $record): bool => !is_null($record->estatus))
                        ]),
                    Action::make('reportar')
                        ->label('Reporte de Actividad')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->authorize('update')
                        ->schema([
                            TextInput::make('cantidad_mujeres')
                                ->label('Cantidad de Mujeres')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                            TextInput::make('cantidad_hombres')
                                ->label('Cantidad de Hombres')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                            Select::make('medios_verificacion_id')
                                ->label('Medio de Verificación')
                                ->options(MedioVerificacion::pluck('nombre', 'id'))
                                ->required()
                        ])
                        ->action(function (array $data, ?Formacion $record): void {
                            if (!$record) {
                                noDisponibleNotification();
                            } else {
                                $record->cantidad_mujeres = $data['cantidad_mujeres'];
                                $record->cantidad_hombres = $data['cantidad_hombres'];
                                $record->medios_verificacion_id = $data['medios_verificacion_id'];
                                $record->estatus = 1;
                                $record->save();
                            }
                        })
                        ->modalIcon(Heroicon::OutlinedCheckCircle)
                        ->modalWidth(Width::Small)
                        ->modalDescription(fn(?Formacion $record) => $record ? getFecha($record->fecha) . ' - ' . Str::upper($record->nombre_obpp) : null)
                        ->hidden(fn(?Formacion $record): bool => $record && !is_null($record->estatus))
                        ->disabled(fn(?Formacion $record): bool => $record && Carbon::parse($record->fecha)->gte(now())),
                    Action::make('no_realizada')
                        ->label('Suspendida')
                        ->icon(Heroicon::OutlinedBackspace)
                        ->requiresConfirmation()
                        ->color('info')
                        ->authorize('update')
                        ->action(function (?Formacion $record): void {
                            if (!$record) {
                                noDisponibleNotification();
                            } else {
                                $record->estatus = 0;
                                $record->save();
                            }
                        })
                        ->modalIcon(Heroicon::OutlinedBackspace)
                        ->modalDescription(fn(?Formacion $record) => $record ? getFecha($record->fecha) . ' - ' . Str::upper($record->nombre_obpp) : null)
                        ->hidden(fn(?Formacion $record): bool => $record && !is_null($record->estatus)),
                    Action::make('reset_actividad')
                        ->label('Reset Actividad')
                        ->requiresConfirmation()
                        ->icon(Heroicon::OutlinedClock)
                        ->action(function (?Formacion $record): void {
                            if (!$record) {
                                noDisponibleNotification();
                            } else {
                                $record->cantidad_mujeres = null;
                                $record->cantidad_hombres = null;
                                $record->medios_verificacion_id = null;
                                $record->estatus = null;
                                $record->save();
                            }
                        })
                        ->modalIcon(Heroicon::OutlinedClock)
                        ->hidden(fn(?Formacion $record): bool => ($record && is_null($record->estatus)) || !isAdmin()),
                    EditAction::make()
                        ->disabled(fn(Formacion $record): bool => !is_null($record->estatus)),
                    Action::make('eliminar')
                        ->label('Borrar')
                        ->icon(Heroicon::Trash)
                        ->color('danger')
                        ->authorize('delete')
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedTrash)
                        ->modalHeading(fn(?Formacion $record) => $record ? 'Borrar ' . Str::upper($record->nombre_obpp) : 'Borrar')
                        ->modalDescription('¿Está segura/o de hacer esto?')
                        ->modalSubmitActionLabel('Borrar')
                        ->action(function (?Formacion $record): void {
                            if (!$record) {
                                noDisponibleNotification();
                            } else {
                                $record->delete();
                                Notification::make()
                                    ->title('Borrado')
                                    ->success()
                                    ->send();
                            }
                        })
                        ->hidden(fn(?Formacion $record): bool => $record && !is_null($record->deleted_at))
                        ->disabled(fn(?Formacion $record): bool => $record && !is_null($record->estatus)),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete'),
                    ForceDeleteBulkAction::make()
                        ->authorizeIndividualRecords('forceDelete'),
                    RestoreBulkAction::make()
                        ->authorizeIndividualRecords('restore'),
                ]),
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('fecha')->heading('FECHA')->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y')),
                        Column::make('redi.nombre')->heading('REDI'),
                        Column::make('estado.nombre')->heading('ESTADO'),
                        Column::make('municipio.nombre')->heading('MUNICIPIO'),
                        Column::make('parroquia')->heading('PARROQUIA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('localidad')->heading('LOCALIDAD')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('cantidad_cc')->heading('CANTIDAD DE CC PARTICIPANTES EN ACTIVIDAD MUNICIPAL/ESTADAL'),
                        Column::make('obpp.nombre')->heading('TIPO DE OBPP')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('situr_obpp')->heading('CÓDIGO SITUR DE LA OBPP')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('nombre_obpp')->heading('NOMBRE DE LA OBPP')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('poblacion.nombre')->heading('TIPO DE CONSEJO COMUNAL/COMUNA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('area.nombre')->heading('TIPO DE PROCESO FORMATIVO (TERRITORIAL)')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('proceso.nombre')->heading('TEMATICA FORMATIVA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('estrategia.nombre')->heading('ESTRATEGIA DE FORMACIÓN')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('modalidad.nombre')->heading('MODALIDAD')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('cantidad_mujeres')->heading('CANTIDAD DE MUJERES'),
                        Column::make('cantidad_hombres')->heading('CANTIDAD DE HOMBRES'),
                        Column::make('medio.nombre')->heading('MEDIO DE VERIFICACIÓN')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('vocero_nombre')->heading('NOMBRE Y APELLIDO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('vocero_telefono')->heading('TELÉFONO'),
                        Column::make('promotor.nombre')->heading('NOMBRE')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('promotor.apellido')->heading('APELLIDO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('promotor.cedula')->heading('CÉDULA'),
                        Column::make('promotor.telefono')->heading('TELÉFONO'),
                        Column::make('promotor.email')->heading('CORREO')->formatStateUsing(fn($state) => Str::lower($state)),
                        Column::make('promotor.ente')->heading('ÓRGANO O ENTE ADSCRITO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('observacion')->heading('OBSERVACIÓN')->formatStateUsing(fn($state) => Str::upper($state)),
                    ])
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton(),
            ])
            ->recordUrl(null);
    }
}

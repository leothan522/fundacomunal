<?php

namespace App\Filament\Resources\Fortalecimientos\Tables;

use App\Filament\Schemas\FechaFilter;
use App\Models\AreaItem;
use App\Models\Fortalecimiento;
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
use Filament\Forms\Components\Textarea;
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

class FortalecimientosTable
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
                    ->default(fn(Fortalecimiento $record) => $record->fecha)
                    ->description(fn(Fortalecimiento $record) => isAdmin() || auth()->user()->hasPermissionTo('jefe_area') ?
                        Str::upper($record->nombre_obpp. ' (' . strtok($record->promotor->nombre, " ") . " " . strtok($record->promotor->apellido, " ") . ')') :
                        Str::upper($record->nombre_obpp))
                    ->date()
                    ->wrap()
                    ->hiddenFrom('md'),
                TextColumn::make('fecha')
                    ->date()
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('nombre_osp')
                    ->label('Nombre de la OSP')
                    ->searchable()
                    ->formatStateUsing(fn($state) => Str::upper($state))
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('nombre_obpp')
                    ->label('Nombre de la OBPP')
                    ->formatStateUsing(fn($state) => Str::upper($state))
                    ->searchable()
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('promotor.nombre')
                    ->formatStateUsing(fn(Fortalecimiento $record) => strtok($record->promotor->nombre, " ") . " " . strtok($record->promotor->apellido, " "))
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
                SelectFilter::make('obpp')
                    ->label('Tipo OSP')
                    ->relationship(
                        'obpp',
                        'nombre',
                        fn(Builder $query) => $query->where('fortalecimiento', 1)
                    ),
                SelectFilter::make('etapa')
                    ->label('Etapa del Proyecto')
                    ->relationship('etapa', 'nombre'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->extraModalFooterActions(fn(): array => [
                            EditAction::make()
                                ->disabled(fn(Fortalecimiento $record): bool => !is_null($record->estatus))
                        ]),
                    Action::make('reportar')
                        ->label('Reporte de Actividad')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->authorize('update')
                        ->schema([
                            TextInput::make('cantidad_familias')
                                ->label('Cantidad de familias beneficiadas')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                            TextInput::make('cantidad_personas')
                                ->label('Cantidad de personas asistentes')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                            Select::make('medios_verificacion_id')
                                ->label('Medio de Verificación')
                                ->options(MedioVerificacion::pluck('nombre', 'id'))
                                ->required(),
                            Textarea::make('observacion')
                                ->label('Observación'),
                        ])
                        ->action(function (array $data, ?Fortalecimiento $record): void {
                            if (!$record) {
                                noDisponibleNotification();
                            } else {
                                $record->cantidad_familias = $data['cantidad_familias'];
                                $record->cantidad_personas = $data['cantidad_personas'];
                                $record->medios_verificacion_id = $data['medios_verificacion_id'];
                                $record->observacion = $data['observacion'];
                                $record->estatus = 1;
                                $record->save();
                            }
                        })
                        ->modalIcon(Heroicon::OutlinedCheckCircle)
                        ->modalWidth(Width::Small)
                        ->modalDescription(fn(?Fortalecimiento $record) => $record ? getFecha($record->fecha) . ' - ' . Str::upper($record->nombre_obpp) : null)
                        ->hidden(fn(?Fortalecimiento $record): bool => $record && !is_null($record->estatus))
                        ->disabled(fn(?Fortalecimiento $record): bool => $record && Carbon::parse($record->fecha)->gte(now())),
                    Action::make('no_realizada')
                        ->label('Suspendida')
                        ->icon(Heroicon::OutlinedBackspace)
                        ->requiresConfirmation()
                        ->color('info')
                        ->authorize('update')
                        ->action(function (?Fortalecimiento $record): void {
                            if (!$record) {
                                noDisponibleNotification();
                            } else {
                                $record->estatus = 0;
                                $record->save();
                            }
                        })
                        ->modalIcon(Heroicon::OutlinedBackspace)
                        ->modalDescription(fn(?Fortalecimiento $record) => $record ? getFecha($record->fecha) . ' - ' . Str::upper($record->nombre_obpp) : null)
                        ->hidden(fn(?Fortalecimiento $record): bool => $record && !is_null($record->estatus)),
                    Action::make('reset_actividad')
                        ->label('Reset Actividad')
                        ->requiresConfirmation()
                        ->icon(Heroicon::OutlinedClock)
                        ->action(function (?Fortalecimiento $record): void {
                            if (!$record) {
                                noDisponibleNotification();
                            } else {
                                $record->cantidad_familias = null;
                                $record->cantidad_personas = null;
                                $record->medios_verificacion_id = null;
                                $record->observacion = null;
                                $record->estatus = null;
                                $record->save();
                            }
                        })
                        ->modalIcon(Heroicon::OutlinedClock)
                        ->hidden(fn(?Fortalecimiento $record): bool => ($record && is_null($record->estatus)) || !isAdmin()),
                    EditAction::make()
                        ->disabled(fn(Fortalecimiento $record): bool => !is_null($record->estatus)),
                    Action::make('eliminar')
                        ->label('Borrar')
                        ->icon(Heroicon::Trash)
                        ->color('danger')
                        ->authorize('delete')
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedTrash)
                        ->modalHeading(fn(?Fortalecimiento $record) => $record ? 'Borrar ' . Str::upper($record->nombre_obpp) : 'Borrar')
                        ->modalDescription('¿Está segura/o de hacer esto?')
                        ->modalSubmitActionLabel('Borrar')
                        ->action(function (?Fortalecimiento $record): void {
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
                        ->hidden(fn(?Fortalecimiento $record): bool => $record && !is_null($record->deleted_at))
                        ->disabled(fn(?Fortalecimiento $record): bool => $record && !is_null($record->estatus)),
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
                        Column::make('nombre_osp')->heading('NOMBRE DE LA ORGANIZACIÓN SOCIO PRODUCTIVA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('rif_osp')->heading('RIF')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('situr_obpp')->heading('CÓDIGO SITUR DE LA OBPP VINCULADA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('nombre_obpp')->heading('NOMBRE DE LA OBPP VINCULADA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('area.nombre')->heading('ACOMPAÑAMIENTO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('proceso.nombre')->heading('PROCESO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('obpp.nombre')->heading('TIPO DE ORGANIZACIÓN SOCIO PRODUCTIVA (OSP)')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('economica.nombre')->heading('TIPO DE ACTIVIDAD ECONOMICA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('cantidad_personas')->heading('CANTIDAD DE PERSONAS ASISTENTES A LA ACTIVIDAD'),
                        Column::make('cantidad_familias')->heading('CANTIDAD DE FAMILIAS BENEFICIADAS POR EL PROYECTO'),
                        Column::make('descripcion_proyecto')->heading('DESCRIPCIÓN DEL PROYECTO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('etapa.nombre')->heading('ETAPA DEL PROYECTO')->formatStateUsing(fn($state) => Str::upper($state)),
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

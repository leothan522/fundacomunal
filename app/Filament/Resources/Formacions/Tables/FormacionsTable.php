<?php

namespace App\Filament\Resources\Formacions\Tables;

use App\Filament\Schemas\FechaFilter;
use App\Filament\Schemas\PromotorFilter;
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
use Illuminate\Support\HtmlString;
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
                    ->description(fn(Formacion $record) => isAdmin() || auth()->user()->hasPermissionTo('jefe_area') ?
                        Str::upper($record->estrategia->nombre.' - '.$record->nombre_obpp. ' (' . strtok($record->promotor->nombre, " ") . " " . strtok($record->promotor->apellido, " ") . ')') :
                        Str::upper($record->estrategia->nombre.' - '.$record->nombre_obpp))
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
                    ->visibleFrom('xl'),
                TextColumn::make('nombre_obpp')
                    ->label('Nombre de la OBPP')
                    ->formatStateUsing(fn($state) => Str::upper($state))
                    ->searchable()
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('promotor.short_name')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
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
                PromotorFilter::schema(),
                SelectFilter::make('area')
                    ->label('Tipo de Proceso')
                    ->relationship(
                        'area',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('area', 'nombre', 'FORMACION')->whereNull('deleted_at') // Excluye los elementos con borrado lógico
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
                    Action::make('compartirWhatsapp')
                        ->label('WhatsApp')
                        ->icon(new HtmlString('
                            <svg class="w-5 h-5 fi-btn-icon" fill="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle;">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.455 5.703 1.456h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        ')) // Icono de compartir (o puedes usar un svg de Whatsapp si prefieres)
                        // Cambiamos el endpoint a api.whatsapp.com
                        ->url(fn (Formacion $record): string => "https://api.whatsapp.com/send?text=" . $record->generarMensajeWhatsApp())
                        ->openUrlInNewTab(),
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

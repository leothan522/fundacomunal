<?php

namespace App\Filament\Resources\Participacions\Tables;

use App\Models\AreaItem;
use App\Models\GestionHumana;
use App\Models\Participacion;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

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
                Filter::make('fecha')
                    ->schema([
                        Select::make('tipo_reporte')
                            ->label('Reporte')
                            ->options([
                                'semana-actual' => 'Semana Actual',
                                'semana-anterior' => 'Semana Anterior',
                                'semana-proxima' => 'Semana Próxima',
                                'mes-actual' => 'Mes Actual',
                                'mes-anterior' => 'Mes Anterior',
                            ])
                    ])
                    ->indicateUsing(function (array $data): ?string{
                        if (!$data['tipo_reporte']) {
                            return null;
                        }
                        return 'Reporte: '.Str::upper($data['tipo_reporte']);
                    })
                    ->query(function (Builder $query, array $data): Builder {

                        if (!$data['tipo_reporte']) {
                            return $query;
                        }

                        $hoy = Carbon::today();
                        $inicio = null;
                        $fin = null;

                        switch ($data['tipo_reporte']) {
                            case 'semana-actual':
                                $inicio = $hoy->copy()->startOfWeek();
                                $fin = $hoy->copy()->endOfWeek();
                                break;

                            case 'semana-anterior':
                                $inicio = $hoy->copy()->subWeek()->startOfWeek();
                                $fin = $hoy->copy()->subWeek()->endOfWeek();
                                break;

                            case 'semana-proxima':
                                $inicio = $hoy->copy()->addWeek()->startOfWeek();
                                $fin = $hoy->copy()->addWeek()->endOfWeek();
                                break;

                            case 'mes-actual':
                                $inicio = $hoy->copy()->startOfMonth();
                                $fin = $hoy->copy()->endOfMonth();
                                break;

                            case 'mes-anterior':
                                $inicio = $hoy->copy()->subMonth()->startOfMonth();
                                $fin = $hoy->copy()->subMonth()->endOfMonth();
                                break;
                        }

                        return $query->whereBetween('fecha', [$inicio, $fin]);

                    }),
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
                        ->modalDescription(fn(Participacion $record) => getFecha($record->fecha) . ' - ' . Str::upper($record->nombre_obpp))
                        ->hidden(fn(Participacion $record): bool => !is_null($record->estatus)),
                    Action::make('no_realizada')
                        ->label('Suspendida')
                        ->icon(Heroicon::OutlinedBackspace)
                        ->requiresConfirmation()
                        ->color('info')
                        ->action(function (Participacion $record): void {
                            $record->estatus = 0;
                            $record->save();
                        })
                        ->modalIcon(Heroicon::OutlinedBackspace)
                        ->modalDescription(fn(Participacion $record) => getFecha($record->fecha) . ' - ' . Str::upper($record->nombre_obpp))
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
                        Column::make('area.nombre')->heading('ACOMPAÑAMIENTO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('proceso.nombre')->heading('PROCESO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('cantidad_familias')->heading('CANTIDAD DE FAMILIAS BENEFICIADAS '),
                        Column::make('cantidad_asistentes')->heading('CANTIDAD DE PERSONAS ASISTENTES A LA ACTIVIDAD'),
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

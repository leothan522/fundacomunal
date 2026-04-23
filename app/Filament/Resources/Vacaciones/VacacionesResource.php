<?php

namespace App\Filament\Resources\Vacaciones;

use App\Filament\Resources\Vacaciones\Pages\ManageVacaciones;
use App\Models\GestionHumana;
use App\Models\Vacaciones;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use UnitEnum;

class VacacionesResource extends Resource
{
    protected static ?string $model = Vacaciones::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSun;

    protected static string|UnitEnum|null $navigationGroup = 'Gestión Humana';

    protected static ?string $recordTitleAttribute = 'periodo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gestion_humana_id')
                    ->relationship('trabajador', 'nombre')
                    ->getOptionLabelFromRecordUsing(fn(GestionHumana $record) => "{$record->nombre} {$record->apellido}")
                    ->searchable(['cedula', 'nombre', 'apellido'])
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('periodo')
                    ->label('Periodo Vacacional')
                    ->placeholder('Ej: 2025-2026')
                    ->required()
                    ->maxLength(9)
                    // 1. Usamos una validación de formato manual en lugar de mask para evitar el bug de JS
                    ->regex('/^\d{4}-\d{4}$/')
                    ->validationMessages([
                        'regex' => 'El formato debe ser YYYY-YYYY (ej. 2024-2025).',
                    ])
                    // 2. Mantenemos la unicidad pero sin el closure que usa $get si es posible
                    ->unique(ignorable: fn($record) => $record, modifyRuleUsing: function ($rule, $get) {
                        $trabajadorId = $get('gestion_humana_id');
                        return $rule->where('gestion_humana_id', $trabajadorId);
                    })
                    ->columnSpanFull(),
                DatePicker::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->required(),
                DatePicker::make('fecha_fin')
                    ->label('Fecha de Culminación')
                    ->required()
                    ->after('fecha_inicio'),
                DatePicker::make('fecha_reintegro')
                    ->label('Fecha de Reintegro')
                    ->required()
                    ->after('fecha_fin'),
                TextInput::make('dias')
                    ->label('Total Días')
                    ->numeric()
                    ->minValue(1)
                    ->prefix('Días'),

                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('created_at'))
            ->recordTitleAttribute('periodo')
            ->columns([
                TextColumn::make('periodo')
                    ->badge()
                    ->searchable(),
                TextColumn::make('vacaciones')
                    ->default(fn(Vacaciones $record): string => Str::upper($record->trabajador->short_name))
                    ->description(fn(Vacaciones $record): string => self::getEstado($record))
                    ->icon(fn(Vacaciones $record): string => match (self::getEstado($record)) {
                        'Próximas' => 'heroicon-m-clock',
                        'En curso' => 'heroicon-m-play',
                        'Por Reintegrar' => 'heroicon-m-calendar-days',
                        'Finalizadas' => 'heroicon-m-check-circle',
                    })
                    ->iconColor(fn(Vacaciones $record): string => match (self::getEstado($record)) {
                        'Próximas' => 'info',
                        'En curso' => 'success',
                        'Por Reintegrar' => 'danger', // Color ámbar para la espera
                        'Finalizadas' => 'gray',
                    })
                    ->hiddenFrom('md'),
                TextColumn::make('trabajador.full_name')
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date()
                    ->alignCenter()
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('fecha_fin')
                    ->label('Culminación')
                    ->date()
                    ->alignCenter()
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('fecha_reintegro')
                    ->label('Reintegro')
                    ->date()
                    ->alignCenter()
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('dias')
                    ->label('Días')
                    ->numeric()
                    ->alignCenter()
                    ->visibleFrom('md'),
                TextColumn::make('estado')
                    ->label('Estado Actual')
                    ->getStateUsing(fn(Vacaciones $record): string => self::getEstado($record))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Próximas' => 'info',
                        'En curso' => 'success',
                        'Por Reintegrar' => 'danger', // Color ámbar para la espera
                        'Finalizadas' => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Próximas' => 'heroicon-m-clock',
                        'En curso' => 'heroicon-m-play',
                        'Por Reintegrar' => 'heroicon-m-calendar-days',
                        'Finalizadas' => 'heroicon-m-check-circle',
                    })
                    ->alignCenter()
                    ->visibleFrom('md'),
            ])
            ->filters([
                SelectFilter::make('trabajador')
                    ->relationship(
                        'trabajador',
                        'id',
                        fn(Builder $query) => $query->orderBy('nombre')
                    )
                    ->getOptionLabelFromRecordUsing(fn(GestionHumana $record) => Str::upper($record->short_name))
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['value']){
                            return null;
                        }
                        $record = GestionHumana::find($data['value']);
                        return 'Trabajador: '.Str::upper($record->short_name);
                    })
                    ->searchable(['nombre', 'apellido'])
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->modalWidth(Width::Small)
                        ->modalDescription(fn(Vacaciones $record): string => Str::upper($record->trabajador->full_name)),
                    DeleteAction::make()
                        ->before(function (Vacaciones $record) {
                            // Modificamos el periodo antes del soft delete
                            if (!str_starts_with($record->periodo, '*')) {
                                $record->update([
                                    'periodo' => '*' . $record->periodo
                                ]);
                            }
                        }),
                    ForceDeleteAction::make(),
                    RestoreAction::make()
                        ->before(function (Vacaciones $record) {
                            // Al restaurar, quitamos el asterisco
                            if (str_starts_with($record->periodo, '*')) {
                                $record->update([
                                    'periodo' => ltrim($record->periodo, '*')
                                ]);
                            }
                        }),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (\Illuminate\Support\Collection $records) {
                            $records->each(function (Vacaciones $record) {
                                if (!str_starts_with($record->periodo, '*')) {
                                    $record->update(['periodo' => '*' . $record->periodo]);
                                }
                            });
                        }),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make()
                        ->before(function (\Illuminate\Support\Collection $records) {
                            $records->each(function (Vacaciones $record) {
                                // Corregido: Si tiene el asterisco, lo removemos para que sea válido otra vez
                                if (str_starts_with($record->periodo, '*')) {
                                    $record->update([
                                        'periodo' => ltrim($record->periodo, '*')
                                    ]);
                                }
                            });
                        }),
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageVacaciones::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    protected static function getEstado($record): string
    {
        $hoy = now()->startOfDay();
        $inicio = \Carbon\Carbon::parse($record->fecha_inicio)->startOfDay();
        $fin = \Carbon\Carbon::parse($record->fecha_fin)->startOfDay();
        $reintegro = \Carbon\Carbon::parse($record->fecha_reintegro)->startOfDay();

        // Si aún no ha llegado la fecha de inicio
        if ($hoy->lt($inicio)) {
            return 'Próximas';
        }

        // Si está entre el inicio y el último día de vacaciones
        if ($hoy->between($inicio, $fin)) {
            return 'En curso';
        }

        // NUEVO: Si ya terminó las vacaciones pero hoy es antes del reintegro
        // (Útil para esos días de fin de semana previos a volver)
        if ($hoy->lt($reintegro)) {
            return 'Por Reintegrar';
        }

        return 'Finalizadas';
    }
}

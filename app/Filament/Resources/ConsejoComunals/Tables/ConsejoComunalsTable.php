<?php

namespace App\Filament\Resources\ConsejoComunals\Tables;

use App\Models\ConsejoComunal;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ConsejoComunalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('consejoComunal')
                    ->default(fn(ConsejoComunal $record): string => Str::upper($record->nombre))
                    ->description(fn(ConsejoComunal $record): string => Str::upper($record->municipio->nombre))
                    ->wrap()
                    ->hiddenFrom('md'),
                TextColumn::make('nombre')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->wrap()
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('situr_viejo')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->searchable()
                    ->alignCenter()
                    ->visibleFrom('md'),
                TextColumn::make('situr_nuevo')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->searchable()
                    ->alignCenter()
                    ->visibleFrom('md'),
                TextColumn::make('tipo.nombre')
                    ->alignCenter()
                    ->visibleFrom('md')
                    ->grow(false),
                TextColumn::make('municipio.nombre')
                    ->wrap()
                    ->visibleFrom('xl')
                    ->grow(false),
                TextColumn::make('status')
                    ->label('ESTATUS')
                    ->badge()
                    ->state(fn (ConsejoComunal $record): string => match (true) {
                        (bool) $record->is_eleccion => 'RENOVADO',
                        empty($record->fecha_vencimiento) => 'SIN REGISTRO',
                        \Carbon\Carbon::parse($record->fecha_vencimiento)->isPast() => 'VENCIDO',
                        default => 'VIGENTE',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'RENOVADO' => 'warning',     // Amarillo
                        'VIGENTE' => 'success',      // Verde
                        'VENCIDO' => 'danger',       // Rojo
                        default => 'gray',           // Gris neutro
                    })
                    ->alignCenter()
                    ->grow(false),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->relationship('tipo', 'nombre'),
                SelectFilter::make('Municipio')
                    ->relationship(
                        'municipio',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('estado', 'nombre', 'GUÁRICO')
                    )
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Comuna')
                    ->label('Comuna o Circuito')
                    ->relationship('comuna', 'nombre')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                    ->extraModalFooterActions(fn(Action $action): array => [
                        EditAction::make(),
                    ]),
                    Action::make('registrarEleccion')
                        ->label('Elección')
                        ->icon(Heroicon::HandRaised)
                        ->color('primary')
                        ->modalHeading('Estatus Elección')
                        ->modalSubmitActionLabel('Guardar')
                        ->modalWidth(Width::Small)
                        ->fillForm(fn (ConsejoComunal $record): array => [
                            'is_eleccion' => $record->is_eleccion ?? false,
                            'fecha_eleccion' => $record->fecha_eleccion ? Carbon::parse($record->fecha_eleccion)->format('Y-m-d') : null,
                        ])
                        ->schema([
                            Toggle::make('is_eleccion')
                                ->label('¿Realizada?')
                                ->live()
                                ->afterStateUpdated(function (Set $set, $state) {
                                    if (! $state) {
                                        $set('fecha_eleccion', null);
                                    }
                                }),

                            DatePicker::make('fecha_eleccion')
                                ->label('Fecha de la elección')
                                ->visible(fn (Get $get): bool => (bool) $get('is_eleccion'))
                                ->required(fn (Get $get): bool => (bool) $get('is_eleccion')),
                        ])
                        ->action(function (ConsejoComunal $record, array $data): void {
                            // Si no se marcó como elección, nos aseguramos de resetear la fecha a null
                            $isEleccion = (bool) ($data['is_eleccion'] ?? false);

                            $record->update([
                                'is_eleccion' => $isEleccion,
                                'fecha_eleccion' => $isEleccion ? $data['fecha_eleccion'] : null,
                            ]);
                        })
                        ->visible(fn (ConsejoComunal $record): bool => ! empty($record->fecha_vencimiento) && Carbon::parse($record->fecha_vencimiento)->isPast()),
                    EditAction::make(),
                    DeleteAction::make(),
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
                        Column::make('municipio.nombre')->heading('MUNICIPIO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('parroquia')->heading('PARROQUIA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('tipo.nombre')->heading('TIPO CONSEJO COMUNAL')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('situr_viejo')->heading('SITUR VIEJO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('situr_nuevo')->heading('SITUR NUEVO OBPP')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('nombre')->heading('CONSEJOS COMUNALES')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('fecha_asamblea')->heading('FECHA DE ASAMBLEA')->formatStateUsing(fn($state) => $state ? getFecha($state) : null),
                        Column::make('fecha_vencimiento')->heading('FECHA DE VENCIMIENTO')->formatStateUsing(fn($state) => $state ? getFecha($state) : null),
                        Column::make('estatus_vencimiento') // Puede llamarse como quieras
                        ->heading('ESTATUS')
                            ->getStateUsing(fn($record) => match (true) {
                                (bool) $record->is_eleccion => 'RENOVADO',
                                empty($record->fecha_vencimiento) => 'SIN REGISTRO',
                                \Carbon\Carbon::parse($record->fecha_vencimiento)->isPast() => 'VENCIDO',
                                default => 'VIGENTE',
                            }),
                        Column::make('fecha_eleccion')
                            ->heading('FECHA DE ELECCIÓN')
                            ->getStateUsing(fn($record) => $record->is_eleccion && $record->fecha_eleccion ? getFecha($record->fecha_eleccion) : null),
                    ])
                        ->withFilename('Consejos_Comunales_'.date('d-m-Y'))
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton()
            ])
            ->recordUrl(false);
    }
}

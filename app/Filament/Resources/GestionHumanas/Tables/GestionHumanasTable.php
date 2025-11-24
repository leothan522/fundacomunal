<?php

namespace App\Filament\Resources\GestionHumanas\Tables;

use App\Models\Categoria;
use App\Models\GestionHumana;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class GestionHumanasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('personal')
                    ->default(fn(GestionHumana $record): string => $record->cedula)
                    ->numeric()
                    ->description(fn(GestionHumana $record): string => $record->nombre . ' ' . $record->apellido)
                    ->wrap()
                    ->hiddenFrom('md'),
                TextColumn::make('cedula')
                    ->label('Cédula')
                    ->numeric()
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('nombre')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('apellido')
                    ->wrap()
                    ->searchable()
                    ->sortable()->visibleFrom('md'),
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable()
                    ->alignCenter()
                    ->visibleFrom('md'),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->limit(20)
                    ->visibleFrom('md'),
                TextColumn::make('tipoPersonal.nombre')
                    ->label('Tipo Personal')
                    ->alignCenter()
                    ->visibleFrom('md'),
                TextColumn::make('categoria.nombre')
                    ->default('-')
                    ->alignCenter()
                    ->wrap()
                    ->visibleFrom('md'),
            ])
            ->filters([
                SelectFilter::make('Tipo Personal')
                    ->relationship('tipoPersonal', 'nombre')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Municipio')
                    ->relationship(
                        'municipio',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('estado', 'nombre', 'GUÁRICO')
                    )
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Categoria')
                    ->relationship('categoria', 'nombre')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('editCategoria')
                        ->label('Categoria')
                        ->icon(Heroicon::OutlinedChevronUpDown)
                        ->fillForm(fn(GestionHumana $record): array => [
                            'categorias_id' => $record->categorias_id
                        ])
                        ->schema([
                            Select::make('categorias_id')
                                ->label('Categoria')
                                ->options(Categoria::query()->pluck('nombre', 'id'))
                                ->searchable()
                                ->required()
                        ])
                        ->action(function (array $data, GestionHumana $record): void {
                            $record->categorias_id = $data['categorias_id'];
                            $record->save();
                            Notification::make()
                                ->title('Categoria Actualizada')
                                ->success()
                                ->send();
                        })
                        ->modalHeading(fn(GestionHumana $record): string => $record->nombre . ' ' . $record->apellido)
                        ->modalWidth(Width::Small)
                    ,
                    EditAction::make(),
                    DeleteAction::make(),
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
                        Column::make('updated_at')->heading('FECHA')->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y')),
                        Column::make('redi.nombre')->heading('REDI'),
                        Column::make('estado.nombre')->heading('ESTADO'),
                        Column::make('municipio.nombre')->heading('MUNICIPIO'),
                        Column::make('parroquia')->heading('PARROQUIA'),
                        Column::make('tipoPersonal.nombre')->heading('LABOR QUE EJERCE'),
                        Column::make('categoria.nombre')->heading('CATEGORIA'),
                        Column::make('nombre')->heading('NOMBRE'),
                        Column::make('apellido')->heading('APELLIDO'),
                        Column::make('cedula')->heading('CÉDULA'),
                        Column::make('telefono')->heading('TELÉFONO'),
                        Column::make('email')->heading('CORREO'),
                        Column::make('ente')->heading('ÓRGANO O ENTE ADSCRITO'),
                        Column::make('observacion')->heading('OBSERVACIÓN'),
                    ])
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton(),
            ]);
    }
}

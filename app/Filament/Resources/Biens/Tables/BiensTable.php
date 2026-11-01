<?php

namespace App\Filament\Resources\Biens\Tables;

use App\Models\Bien;
use App\Models\BienCondicion;
use App\Models\BienTipo;
use App\Models\BienUbicacion;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
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

class BiensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('telefono')
                    ->default(fn(Bien $record) => Str::upper($record->serial))
                    ->description(
                        function (Bien $record): string {
                            $marca = $record->marca->nombre ?? "";
                            $modelo = $record->modelo->nombre ?? "";
                            $color = $record->color->nombre ?? "";
                            $texto = $record->tipo->nombre . " " . $marca . " " . $modelo . " " . $color;
                            return Str::upper($texto);
                        }
                    )
                    ->wrap()
                    ->hiddenFrom('md'),
                TextColumn::make('serial')
                    ->label('Serial / ID')
                    ->formatStateUsing(fn(string $state) => Str::upper($state))
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('tipo.nombre')
                    ->label('Tipo / Categoría')
                    ->formatStateUsing(fn(string $state) => Str::upper($state))
                    ->alignCenter()
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->placeholder('-')
                    ->formatStateUsing(fn(string $state) => Str::upper($state))
                    ->alignCenter()
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('modelo.nombre')
                    ->label('Modelo')
                    ->placeholder('-')
                    ->formatStateUsing(fn(string $state) => Str::upper($state))
                    ->alignCenter()
                    ->searchable()
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('color.nombre')
                    ->label('Color')
                    ->placeholder('-')
                    ->formatStateUsing(fn(string $state) => Str::upper($state))
                    ->alignCenter()
                    ->searchable()
                    ->visibleFrom('xl'),
                TextColumn::make('ubicacion.nombre')
                    ->label('Ubicación')
                    ->placeholder('-')
                    ->formatStateUsing(fn(string $state) => Str::upper($state))
                    ->alignCenter()
                    ->searchable()
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('condicion.nombre')
                    ->label('Condición')
                    ->placeholder('-')
                    ->formatStateUsing(fn(string $state) => Str::upper($state))
                    ->alignCenter()
                    ->color(fn(string $state): string => match (mb_strtoupper($state)) {
                        'OPERATIVO' => 'success',
                        'REGULAR' => 'warning',
                        'INOPERATIVO', 'DAÑADO' => 'danger',
                        default => 'gray',
                    })
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo / Categoría')
                    ->relationship(
                        name: 'tipo',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: fn(Builder $query) => $query->orderBy('nombre')
                    )
                    ->getOptionLabelFromRecordUsing(fn(BienTipo $record) => Str::upper($record->nombre))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('ubicacion')
                    ->label('Ubicación')
                    ->relationship(
                        name: 'ubicacion',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: fn(Builder $query) => $query->orderBy('nombre')
                    )
                    ->getOptionLabelFromRecordUsing(fn(BienUbicacion $record) => Str::upper($record->nombre))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('condicion')
                    ->label('Condición')
                    ->relationship(
                        name: 'condicion',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: fn(Builder $query) => $query->orderBy('nombre')
                    )
                    ->getOptionLabelFromRecordUsing(fn(BienCondicion $record) => Str::upper($record->nombre))
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
                        ->authorizeIndividualRecords('restore')
                ]),
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('tipo.nombre')->heading('Tipo / Categoría')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('serial')->heading('Serial / Identificador')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('ubicacion.nombre')->heading('Ubicación')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('condicion.nombre')->heading('Condición')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('marca.nombre')->heading('Marca')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('modelo.nombre')->heading('Modelo')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('color.nombre')->heading('Color')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('informacion_adicional')->heading('Información Adicional')->formatStateUsing(fn($state) => Str::upper($state)),
                    ])
                        ->withFilename('Bienes_Fundacomunal_' . date('d-m-Y'))
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton()
            ])
            ->recordUrl(false);
    }
}

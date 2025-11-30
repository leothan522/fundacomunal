<?php

namespace App\Filament\Resources\Comunas\Tables;

use App\Models\Comuna;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
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

class ComunasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('comuna')
                    ->label('Cirtuito o Comuna')
                    ->default(fn(Comuna $record): string => Str::upper($record->nombre))
                    ->description(fn(Comuna $record): string => Str::upper($record->municipio->nombre))
                    ->wrap()
                    ->hiddenFrom('md'),
                TextColumn::make('nombre')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->searchable()
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('cod_com')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->label('COD. COM')
                    ->searchable()
                    ->alignCenter()
                    ->grow(false)
                    ->visibleFrom('md'),
                TextColumn::make('cod_situr')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->label('COD. SITUR')
                    ->searchable()
                    ->alignCenter()
                    ->grow(false)
                    ->visibleFrom('md'),
                TextColumn::make('cantidad_cc')
                    ->label('Cantidad C.C.')
                    ->numeric()
                    ->alignCenter()
                    ->grow(false)
                    ->visibleFrom('md'),
                TextColumn::make('consejos_count')->counts('consejos')
                    ->label('C.C.')
                    ->alignCenter()
                    ->grow(false),
                TextColumn::make('municipio.nombre')
                    ->wrap()
                    ->visibleFrom('md'),
            ])
            ->filters([
                SelectFilter::make('Municipio')
                    ->relationship(
                        'municipio',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('estado', 'nombre', 'GUÁRICO')
                    )
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
                        ->authorizeIndividualRecords('restore'),
                ]),
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('municipio.nombre')->heading('MUNICIPIO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('parroquia')->heading('PARROQUIA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('cod_com')->heading('COD. COM')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('cod_situr')->heading('COD. SITUR')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('nombre')->heading('CIRCUITO O COMUNA')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('cantidad_cc')->heading('CANDIDAD C.C.')->formatStateUsing(fn($record) => $record->consejos->count()),
                    ])
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton()
            ])
            ->recordUrl(false);
    }
}

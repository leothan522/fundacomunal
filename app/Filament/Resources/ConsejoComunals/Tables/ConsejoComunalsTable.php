<?php

namespace App\Filament\Resources\ConsejoComunals\Tables;

use App\Models\ConsejoComunal;
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
                    ->visibleFrom('md')
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

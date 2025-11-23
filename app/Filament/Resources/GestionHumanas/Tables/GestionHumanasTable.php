<?php

namespace App\Filament\Resources\GestionHumanas\Tables;

use App\Models\GestionHumana;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    ->numeric()
                    ->sortable()
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
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton(),
            ]);
    }
}

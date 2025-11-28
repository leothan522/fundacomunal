<?php

namespace App\Filament\Resources\Comunas\Tables;

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
use Illuminate\Support\Str;

class ComunasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('cod_com')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->label('COD. COM')
                    ->searchable()
                    ->alignCenter()
                    ->grow(false),
                TextColumn::make('cod_situr')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->label('COD. SITUR')
                    ->searchable()
                    ->alignCenter()
                    ->grow(false),
                TextColumn::make('cantidad_cc')
                    ->label('Cantidad C.C.')
                    ->numeric()
                    ->alignCenter()
                    ->grow(false),
                TextColumn::make('consejos_count')->counts('consejos')
                    ->label('Count')
                    ->alignCenter(),
                TextColumn::make('municipio.nombre')
                    ->wrap(),
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
                    ->iconButton()
            ]);
    }
}

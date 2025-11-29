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
                TextColumn::make('tipo')
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
                    ->options([
                        'RURAL' => 'RURAL',
                        'URBANO' => 'URBANO',
                        'INDIGENA' => 'INDIGENA',
                        'MIXTO' => 'MIXTO',
                    ]),
                SelectFilter::make('Municipio')
                    ->relationship(
                        'municipio',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('estado', 'nombre', 'GUÁRICO')
                    )
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Comuna')
                    ->label('Circuito o Comuna')
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
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton()
            ])
            ->recordUrl(false);
    }
}

<?php

namespace App\Filament\Resources\Participacions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ParticipacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('redis_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estados_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('municipios_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('parroquia')
                    ->searchable(),
                TextColumn::make('localidad')
                    ->searchable(),
                TextColumn::make('cantidad_cc')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tipos_obpp_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('situr_obpp')
                    ->searchable(),
                TextColumn::make('nombre_obpp')
                    ->searchable(),
                TextColumn::make('tipos_poblacion_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('areas_items_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('areas_procesos_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cantidad_familias')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cantidad_asistentes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('vocero_nombre')
                    ->searchable(),
                TextColumn::make('vocero_telefono')
                    ->searchable(),
                TextColumn::make('gestion_humana_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('comunas_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('consejos_comunales_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

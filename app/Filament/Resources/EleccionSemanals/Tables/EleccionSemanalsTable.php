<?php

namespace App\Filament\Resources\EleccionSemanals\Tables;

use App\Models\Participacion;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EleccionSemanalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    TextColumn::make('nombre_obpp')
                        ->weight('bold')
                        ->size('md')
                        ->color('primary')
                        ->wrap()
                        ->searchable(),

                    Split::make([
                        TextColumn::make('situr_obpp')
                            ->label('SITUR')
                            ->icon('heroicon-m-hashtag')
                            ->color('gray')
                            ->size('sm'),

                        TextColumn::make('fecha')
                            ->date('d/m/Y')
                            ->badge()
                            ->color('info')
                            ->alignEnd(),
                    ]),

                    TextColumn::make('localidad')
                        ->icon('heroicon-m-map-pin')
                        ->size('xs')
                        ->color('gray')
                        ->description(fn(Participacion $record) => "Mun. {$record->municipio?->nombre} - Parroquia: {$record->parroquia}"),
                ])->space(3),
            ])
            ->recordActions([
                ViewAction::make()
            ])
            ->defaultSort('fecha', 'desc')
            ->groups([
                Group::make('gestion_humana_id')
                    ->label('Promotor')
                    ->getTitleFromRecordUsing(fn(Participacion $record) => $record->promotor?->short_name ?? 'Sin asignar')
                    ->collapsible(),
            ])
            ->defaultGroup('gestion_humana_id')
            ->filters([
                SelectFilter::make('municipios_id')
                    ->label('Municipio')
                    ->relationship('municipio', 'nombre')
                    ->searchable()
                    ->preload(),

                Filter::make('esta_semana')
                    ->label('Solo esta semana')
                    ->query(fn (Builder $query) => $query->whereBetween('fecha', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]))
                    ->toggle()
                    ->default(), // Activado por defecto para agilizar la carga
            ]);
    }
}

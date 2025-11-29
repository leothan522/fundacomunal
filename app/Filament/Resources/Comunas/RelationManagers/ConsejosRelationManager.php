<?php

namespace App\Filament\Resources\Comunas\RelationManagers;

use App\Models\ConsejoComunal;
use Filament\Actions\ActionGroup;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ConsejosRelationManager extends RelationManager
{
    protected static string $relationship = 'consejos';
    protected static ?string $title = 'Consejos Comunales';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('consejoComunal')
                    ->default(fn(ConsejoComunal $record): string => Str::upper($record->nombre))
                    ->description(fn(ConsejoComunal $record): string => Str::upper($record->parroquia))
                    ->hiddenFrom('md'),
                TextColumn::make('nombre')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->searchable()
                    ->wrap()
                    ->visibleFrom('md'),
                TextColumn::make('parroquia')
                    ->visibleFrom('md'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AssociateAction::make()
                    ->recordSelectSearchColumns(['nombre', 'situr_viejo', 'situr_nuevo']),
            ])
            ->recordActions([
                ActionGroup::make([
                    DissociateAction::make()
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                ]),
            ]);
    }
}

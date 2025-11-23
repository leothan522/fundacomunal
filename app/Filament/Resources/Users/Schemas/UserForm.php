<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos Básicos')
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->maxLength(255)
                            ->unique()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->maxLength(15)
                            ->required()
                            ->hiddenOn('edit'),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                            ->required(),
                    ])
                    ->compact()
                    ->columns()
                    ->columnSpanFull(),
                Section::make('Permisos')
                    ->schema([
                        Toggle::make('access_panel')
                            ->inline(false)
                            ->required(),
                        Select::make('roles')
                            ->label(__('Role'))
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->requiredIf('access_panel', true)
                    ])
                    ->compact()
                    ->columns()
                    ->columnSpanFull(),
                Text::make(fn(User $record): string => $record->login_count.' Visitas')
                    ->size(TextSize::Medium)
                    ->badge()
                    ->icon(Heroicon::OutlinedFlag)
                    ->hiddenOn('create'),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->hiddenOn('create')
            ]);
    }
}

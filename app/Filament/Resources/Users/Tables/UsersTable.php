<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user')
                    ->label(__('User'))
                    ->default(fn(User $record): string => $record->name)
                    ->description(fn(User $record): string => $record->email)
                    ->wrap()
                    ->icon(fn(User $record): Heroicon => match (self::getEstatus($record)) {
                        'activo' => Heroicon::OutlinedShieldCheck,
                        'inactivo' => Heroicon::OutlinedNoSymbol,
                        default => Heroicon::OutlinedClock
                    })
                    ->iconColor(fn(User $record): string => match (self::getEstatus($record)) {
                        'activo' => 'success',
                        'inactivo' => 'danger',
                        default => 'gray'
                    })
                    ->formatStateUsing(fn(string $state): string => Str::ucwords($state))
                    ->hiddenFrom('md'),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->icon(fn(User $record): Heroicon => match (self::getEstatus($record)) {
                        'activo' => Heroicon::OutlinedShieldCheck,
                        'inactivo' => Heroicon::OutlinedNoSymbol,
                        default => Heroicon::OutlinedClock
                    })
                    ->iconColor(fn(User $record): string => match (self::getEstatus($record)) {
                        'activo' => 'success',
                        'inactivo' => 'danger',
                        default => 'gray'
                    })
                    ->formatStateUsing(fn(string $state): string => Str::ucwords($state))
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->alignCenter()
                    ->searchable()
                    ->visibleFrom('md'),
                IconColumn::make('email_verified_at')
                    ->label('Verificado')
                    ->default(false)
                    ->boolean()
                    ->falseIcon(Heroicon::OutlinedClock)
                    ->falseColor('gray')
                    ->alignCenter()
                    ->visibleFrom('md'),
                ImageColumn::make('profile_photo_path')
                    ->label(__('Image'))
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(verImagen(null, true))
                    ->alignCenter()
                    ->visibleFrom('md'),
                TextColumn::make('login_count')
                    ->label('Visitas')
                    ->icon(Heroicon::OutlinedFlag)
                    ->numeric()
                    ->alignCenter()
                    ->visibleFrom('md')
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('reset_password')
                        ->label(__('Reset Password'))
                        ->icon(Heroicon::OutlinedKey)
                        ->schema([
                            TextInput::make('new_password')
                                ->label(__('New Password'))
                                ->password()
                                ->revealable()
                                ->minLength(8)
                                ->maxLength(15)
                                ->required(),
                        ])
                        ->action(function (array $data, User $record): void {
                            $record->password = Hash::make($data['new_password']);
                            $record->save();
                        })
                        ->modalWidth(Width::Small)
                        ->hidden(fn(User $record): bool => (auth()->id() == $record->id) || !isAdmin() || $record->is_root)
                        ->disabled(fn(User $record): bool => (auth()->id() == $record->id) || !isAdmin() || $record->is_root),
                    Action::make('validar_email')
                        ->label('Verificar Email')
                        ->icon(Heroicon::CheckCircle)
                        ->action(function (User $record): void {
                            $record->email_verified_at = now();
                            $record->save();
                        })
                        ->requiresConfirmation()
                        ->hidden(fn(User $record): bool => !is_null($record->email_verified_at))
                        ->disabled(fn(User $record): bool => (auth()->id() == $record->id) || !isAdmin() || $record->is_root),
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
                    RestoreBulkAction::make(),
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton(),
            ]);
    }

    protected static function getEstatus($record): string
    {
        $validado = $record->email_verified_at ?? false;
        $response = 'default';
        if ($validado) {
            if ($record->is_active || $record->is_root) {
                $response = 'activo';
            } else {
                $response = 'inactivo';
            }
        }
        return $response;
    }

}

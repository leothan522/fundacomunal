<?php

namespace App\Filament\Resources\GestionHumanas\Tables;

use App\Models\Categoria;
use App\Models\GestionHumana;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class GestionHumanasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('personal')
                    ->default(fn(GestionHumana $record): string => $record->cedula)
                    ->numeric()
                    ->description(fn(GestionHumana $record): string => Str::upper($record->nombre . ' ' . $record->apellido))
                    ->wrap()
                    ->hiddenFrom('md'),
                TextColumn::make('cedula')
                    ->label('Cédula')
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('nombre')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->visibleFrom('md'),
                TextColumn::make('apellido')
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
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
                    ->formatStateUsing(fn(string $state): string => Str::lower($state))
                    ->searchable()
                    ->limit(20)
                    ->visibleFrom('md'),
                TextColumn::make('tipoPersonal.nombre')
                    ->label('Labor que Ejerce')
                    ->alignCenter()
                    ->visibleFrom('md'),
                TextColumn::make('categoria.nombre')
                    ->default('-')
                    ->alignCenter()
                    ->wrap()
                    ->visibleFrom('md'),
            ])
            ->filters([
                SelectFilter::make('Labor que Ejerce')
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
                    Action::make('editCategoria')
                        ->label('Categoria')
                        ->icon(Heroicon::OutlinedChevronUpDown)
                        ->fillForm(fn(GestionHumana $record): array => [
                            'categorias_id' => $record->categorias_id
                        ])
                        ->schema([
                            Select::make('categorias_id')
                                ->label('Categoria')
                                ->options(Categoria::query()->pluck('nombre', 'id'))
                                ->searchable()
                                ->required()
                        ])
                        ->action(function (array $data, GestionHumana $record): void {
                            $record->categorias_id = $data['categorias_id'];
                            $record->save();
                            Notification::make()
                                ->title('Categoria Actualizada')
                                ->success()
                                ->send();
                        })
                        ->modalHeading(fn(GestionHumana $record): string => $record->nombre . ' ' . $record->apellido)
                        ->modalWidth(Width::Small),
                    ViewAction::make()
                        ->extraModalFooterActions(fn(Action $action): array => [
                            EditAction::make()
                        ]),
                    Action::make('crearUsuario')
                        ->label('Crear Usuario')
                        ->icon(Heroicon::OutlinedUserPlus)
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedUserPlus)
                        ->hidden(fn(GestionHumana $record): bool => !empty($record->users_id))
                        ->action(function (GestionHumana $record): void {
                            self::createUser($record);
                        }),
                    Action::make('resetUsuario')
                        ->label('Actualizar Usuario')
                        ->icon(Heroicon::OutlinedUserCircle)
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedUserCircle)
                        ->hidden(function (GestionHumana $record): bool {
                            $response = true;
                            if ($record->users_id && !$record->user->login_count){
                                $response = false;
                            }
                            return $response;
                        })
                        ->action(function (GestionHumana $record): void {
                            self::resetUser($record);
                        }),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make()
                        ->authorizeIndividualRecords('forceDelete'),
                    RestoreBulkAction::make()
                        ->authorizeIndividualRecords('restore'),
                ]),
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('updated_at')->heading('FECHA')->formatStateUsing(fn($state) => Carbon::now()->format('d/m/Y')),
                        Column::make('redi.nombre')->heading('REDI'),
                        Column::make('estado.nombre')->heading('ESTADO'),
                        Column::make('municipio.nombre')->heading('MUNICIPIO'),
                        Column::make('parroquia')->heading('PARROQUIA'),
                        Column::make('tipoPersonal.nombre')->heading('LABOR QUE EJERCE'),
                        Column::make('categoria.nombre')->heading('CATEGORIA'),
                        Column::make('nombre')->heading('NOMBRE')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('apellido')->heading('APELLIDO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('cedula')->heading('CÉDULA'),
                        Column::make('telefono')->heading('TELÉFONO'),
                        Column::make('email')->heading('CORREO')->formatStateUsing(fn($state) => Str::lower($state)),
                        Column::make('ente')->heading('ÓRGANO O ENTE ADSCRITO')->formatStateUsing(fn($state) => Str::upper($state)),
                        Column::make('observacion')->heading('OBSERVACIÓN')->formatStateUsing(fn($state) => Str::upper($state)),
                    ])
                ]),
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton(),
            ])
            ->recordUrl(null);
    }

    public static function createUser($record): void
    {
        $crear = true;
        $error = null;

        if (empty($record->email)) {
            $crear = false;
            $error = "Falta el correo del trabajador";
        }

        if (User::withTrashed()->where('email', $record->email)->exists()) {
            $crear = false;
            $error = "Ya existe un usuario con el correo del trabajador";
        }

        if ($crear) {
            $user = User::factory()->create([
                'name' => strtok($record->nombre, " ") . ' ' . strtok($record->apellido, " "),
                'email' => Str::lower($record->email),
                'password' => Hash::make($record->cedula),
                'phone' => $record->telefono,
                'access_panel' => 1
            ]);

            if ($record->tipoPersonal->nombre == 'PROMOTORES') {
                $user->assignRole('PARTICIPACION');
            }

            if ($record->tipoPersonal->nombre == 'COORDINADOR(A) ESTADAL') {
                $user->assignRole('admin');
            }

            $record->users_id = $user->id;
            $record->save();

            Notification::make()
                ->title('Usuario Creado')
                ->success()
                ->send();

        } else {
            Notification::make()
                ->title('No se pudo crear el usuario')
                ->danger()
                ->body($error)
                ->send();
        }
    }

    public static function resetUser($record): void
    {
        $crear = true;
        $error = null;

        if (empty($record->email)) {
            $crear = false;
            $error = "Falta el correo del trabajador";
        }

        if (User::withTrashed()->where('email', $record->email)->where('id', '!=', $record->users_id)->exists()) {
            $crear = false;
            $error = "Ya existe un usuario con el correo del trabajador";
        }

        if ($crear) {
            $user = User::find($record->users_id);
            $user->name = strtok($record->nombre, " ").' '.strtok($record->apellido, " ");
            $user->email = $record->email;
            $user->password = Hash::make($record->cedula);
            $user->phone = $record->telefono;
            $user->is_active = 1;
            $user->access_panel = 1;
            $user->save();

            if ($record->tipoPersonal->nombre == 'PROMOTORES') {
                $user->assignRole('PARTICIPACION');
            }

            if ($record->tipoPersonal->nombre == 'COORDINADOR(A) ESTADAL') {
                $user->assignRole('admin');
            }

            Notification::make()
                ->title('Usuario Actualizado')
                ->success()
                ->send();

        } else {
            Notification::make()
                ->title('No se pudo Actualizar el usuario')
                ->danger()
                ->body($error)
                ->send();
        }

    }
}

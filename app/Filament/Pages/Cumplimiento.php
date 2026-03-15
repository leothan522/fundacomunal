<?php

namespace App\Filament\Pages;

use App\Models\GestionHumana;
use App\Models\Participacion;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use UnitEnum;

class Cumplimiento extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected string $view = 'filament.pages.cumplimiento';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static string|UnitEnum|null $navigationGroup = 'Planificación Semanal';
    protected static ?int $navigationSort = 70;

    public static function canAccess(): bool
    {
        return isAdmin() ||
            auth()->user()->hasRole('GESTION HUMANA') ||
            auth()->user()->hasRole('PARTICIPACION') ||
            auth()->user()->hasRole('FORMACION') ||
            auth()->user()->hasRole('FORTALECIMIENTO');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = GestionHumana::query();
                $query->whereRelation('tipoPersonal', 'nombre', 'PROMOTORES');
                if (!$this->showComponent()) {
                    $query->whereRelation('user', 'id', auth()->id());
                }
                return $query->orderBy('nombre');
            })
            ->columns([
                TextColumn::make('nombre')
                    ->label('Promotor')
                    ->formatStateUsing(fn(GestionHumana $record): string => Str::upper($record->short_name))
                    ->searchable($this->showComponent())
                    ->icon(Heroicon::OutlinedUser)
                    ->size(TextSize::Large)
                    ->weight(FontWeight::Bold)
                    ->badge()
                    ->color('info'),
                TextColumn::make('user_estatus')
                    ->label('Estatus')
                    ->getStateUsing(fn(GestionHumana $record): string => $this->getUsuarioEstatus($record))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'activo' => 'success',
                        default => 'danger'
                    })
                    ->alignCenter(),
                TextColumn::make('act_cargadas')
                    ->label('Act. Cargadas')
                    ->getStateUsing(fn(GestionHumana $record): int => $this->getActCargadas($record))
                    ->numeric()
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state > 1 => 'success',
                        default => 'danger'
                    })
                    ->alignCenter(),
                TextColumn::make('act_reportadas')
                    ->label('Act. Reportadas')
                    ->getStateUsing(fn(GestionHumana $record): int => $this->getReportadas($record))
                    ->numeric()
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state > 0 => 'success',
                        default => 'danger'
                    })
                    ->alignCenter(),
                TextColumn::make('act_suspendidas')
                    ->label('Act. Suspendidas')
                    ->getStateUsing(fn(GestionHumana $record): int => $this->getSuspendidas($record))
                    ->numeric()
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state > 0 => 'info',
                        default => 'gray'
                    })
                    ->alignCenter(),
                TextColumn::make('act_pendientes')
                    ->label('Act. Pendientes')
                    ->getStateUsing(fn(GestionHumana $record): int => $this->getPendientes($record))
                    ->numeric()
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state > 0 => 'primary',
                        default => 'gray'
                    })
                    ->alignCenter(),
                TextColumn::make('planificacion_semanal')
                    ->label('Planificación Semanal')
                    ->getStateUsing(fn(GestionHumana $record): string => $this->getPlanificacionSemanal($record))
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'entregada' => 'ENTREGADA',
                        'semana_proxima' => 'FALTA SEMANA PROXIMA',
                        default => 'NO ENTREGADA'
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'entregada' => 'success',
                        'semana_proxima' => 'primary',
                        default => 'danger'
                    })
                    ->alignCenter()
            ])
            ->stackedOnMobile()
            ->filters([
                SelectFilter::make('municipios')
                    ->relationship(
                        'municipio',
                        'nombre',
                        fn(Builder $query) => $query->whereRelation('estado', 'nombre', 'GUARICO')
                    )
                    ->searchable()
                    ->preload()
                    ->visible($this->showComponent())

            ])
            ->toolbarActions([
                Action::make('actualizar')
                    ->icon(Heroicon::ArrowPath)
                    ->iconButton()
                    ->visible($this->showComponent()),
            ])
            ->paginated($this->showComponent());
    }

    protected function showComponent(): bool
    {
        return isAdmin() || auth()->user()->hasRole('GESTION HUMANA');
    }

    protected function getUsuarioEstatus(GestionHumana $record): string
    {
        $response = 'inactivo';
        if ($record->user()->exists()) {
            if ($record->user->email_verified_at) {
                if ($record->participacion()->count() || $record->formacion()->count() || $record->fortalecimiento()->count()) {
                    $response = 'activo';
                }
            } else {
                $response = 'no_verified_email';
            }
        }

        return $response;
    }

    protected function getActCargadas(GestionHumana $record): int
    {
        $participacion = $record->participacion()->count();
        $formacion = $record->formacion()->count();
        $fortalezomiento = $record->fortalecimiento()->count();
        return $participacion + $formacion + $fortalezomiento;
    }

    protected function getReportadas(GestionHumana $record): int
    {
        $participacion = $record->participacion()->where('estatus', 1)->count();
        $formacion = $record->formacion()->where('estatus', 1)->count();
        $fortalezomiento = $record->fortalecimiento()->where('estatus', 1)->count();
        return $participacion + $formacion + $fortalezomiento;
    }

    protected function getSuspendidas(GestionHumana $record): int
    {
        $participacion = $record->participacion()->where('estatus', 0)->count();
        $formacion = $record->formacion()->where('estatus', 0)->count();
        $fortalezomiento = $record->fortalecimiento()->where('estatus', 0)->count();
        return $participacion + $formacion + $fortalezomiento;
    }

    protected function getPendientes(GestionHumana $record): int
    {
        $participacion = $record->participacion()->whereNull('estatus')->count();
        $formacion = $record->formacion()->whereNull('estatus')->count();
        $fortalezomiento = $record->fortalecimiento()->whereNull('estatus')->count();
        return $participacion + $formacion + $fortalezomiento;
    }

    protected function getPlanificacionSemanal(GestionHumana $record): string
    {
        $response = 'no_entregada';
        $hoy = Carbon::today();
        $inicioSemanaActual = $hoy->copy()->startOfWeek();
        $finSemanaActual = $hoy->copy()->endOfWeek();
        $inicioSemanaProxima = $hoy->copy()->addWeek()->startOfWeek();
        $finSemanaProxima = $hoy->copy()->addWeek()->endOfWeek();

        $participacion = $record->participacion()->whereBetween('fecha', [$inicioSemanaActual, $finSemanaActual])->count();
        $formacion = $record->formacion()->whereBetween('fecha', [$inicioSemanaActual, $finSemanaActual])->count();
        $fortalecimiento = $record->fortalecimiento()->whereBetween('fecha', [$inicioSemanaActual, $finSemanaActual])->count();

        $cumplimientoActual = $participacion + $formacion + $fortalecimiento;

        $participacion = $record->participacion()->whereBetween('fecha', [$inicioSemanaProxima, $finSemanaProxima])->count();
        $formacion = $record->formacion()->whereBetween('fecha', [$inicioSemanaProxima, $finSemanaProxima])->count();
        $fortalecimiento = $record->fortalecimiento()->whereBetween('fecha', [$inicioSemanaProxima, $finSemanaProxima])->count();

        $cumplimientoProxima = $participacion + $formacion + $fortalecimiento;

        if ($cumplimientoActual) {
            if ($cumplimientoProxima) {
                $response = 'entregada';
            } else {
                $response = 'semana_proxima';
            }
        }

        return $response;
    }


}

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
use UnitEnum;

class Cumplimiento extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected string $view = 'filament.pages.cumplimiento';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static string|UnitEnum|null $navigationGroup = 'Áreas Sustantivas';
    protected static ?int $navigationSort = 70;

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
                    ->searchable($this->showComponent())
                    ->hiddenFrom('md')
                    ->icon(Heroicon::OutlinedUser)
                    ->size(TextSize::Medium)
                    ->weight(FontWeight::Bold)
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn(GestionHumana $record): string => strtok($record->nombre, " ") . ' ' . strtok($record->apellido, " ")),
                TextColumn::make('apellido')
                    ->label('Promotor')
                    ->searchable($this->showComponent())
                    ->visibleFrom('md')
                    ->icon(Heroicon::OutlinedUser)
                    ->size(TextSize::Medium)
                    ->weight(FontWeight::Bold)
                    ->badge()
                    ->color('info')
                    ->verticallyAlignCenter()
                    ->formatStateUsing(fn(GestionHumana $record): string => strtok($record->nombre, " ") . ' ' . strtok($record->apellido, " ")),
                Split::make([
                    TextColumn::make('blank1'),
                    TextColumn::make('blank2'),
                    TextColumn::make('blank3'),
                    $this->createIconColumn('anterior'),
                    $this->createIconColumn('actual'),
                    $this->createIconColumn('proxima'),
                ])
            ])
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

    protected function createIconColumn(string $semana)
    {
        return IconColumn::make($semana)
            ->default(fn(GestionHumana $record) => $this->getReporte($semana, $record))
            ->icon(fn($state): Heroicon => match ($state) {
                1 => Heroicon::OutlinedCheckCircle,
                2 => Heroicon::OutlinedExclamationCircle,
                default => Heroicon::OutlinedXCircle,
            })
            ->color(fn($state): string => match ($state) {
                1 => 'success',
                2 => 'warning',
                default => 'danger'
            })
            ->alignCenter();
    }

    protected function getReporte(string $semana, GestionHumana $record): int
    {
        $hoy = Carbon::today();
        $inicio = null;
        $fin = null;

        switch ($semana) {
            case 'actual':
                $inicio = $hoy->copy()->startOfWeek();
                $fin    = $hoy->copy()->endOfWeek();
                break;

            case 'anterior':
                $inicio = $hoy->copy()->subWeek()->startOfWeek();
                $fin    = $hoy->copy()->subWeek()->endOfWeek();
                break;

            case 'proxima':
                $inicio = $hoy->copy()->addWeek()->startOfWeek();
                $fin    = $hoy->copy()->addWeek()->endOfWeek();
                break;
        }

        $participacion = Participacion::query()
            ->where('gestion_humana_id', $record->id)
            ->whereBetween('fecha', [$inicio, $fin]);

        $planificadas = $participacion->count();

        // regla de "a tiempo"
        if ($semana === 'proxima') {
            // lunes a miércoles de la semana actual
            $lunesActual     = $hoy->copy()->startOfWeek();
            $miercolesActual = $lunesActual->copy()->addDays(2);

            $aTiempo = $participacion
                ->whereBetween('created_at', [$lunesActual, $miercolesActual])
                ->count();
        } elseif ($semana === 'actual') {
            // lunes a miércoles de la semana pasada
            $lunesPasada     = $hoy->copy()->subWeek()->startOfWeek();
            $miercolesPasada = $lunesPasada->copy()->addDays(2);

            $aTiempo = $participacion
                ->whereBetween('created_at', [$lunesPasada, $miercolesPasada])
                ->count();
        } elseif ($semana === 'anterior') {
            // lunes a miércoles de la semana previa a la evaluada
            $lunesPrevio     = $inicio->copy()->subWeek()->startOfWeek();
            $miercolesPrevio = $lunesPrevio->copy()->addDays(2);

            $aTiempo = $participacion
                ->whereBetween('created_at', [$lunesPrevio, $miercolesPrevio])
                ->count();
        } else {
            $aTiempo = 0;
        }

        // regla de reportadas
        if ($semana === 'actual') {
            $reportadas = $participacion
                ->whereNotNull('estatus')
                ->where('fecha', '<=', $hoy)
                ->count();
        } elseif ($semana === 'proxima') {
            $reportadas = null; // no se evalúan
        } else {
            $reportadas = $participacion->whereNotNull('estatus')->count();
        }

        // evaluación final
        if ($planificadas === 0) {
            $resultado = 0;
        } elseif ($semana === 'proxima') {
            $resultado = $aTiempo > 0 ? 1 : 2; // semana próxima depende solo de entregas a tiempo
        } elseif ($aTiempo > 0) {
            if ($reportadas !== null && $reportadas === $planificadas) {
                $resultado = 1; // todas reportadas y al menos una a tiempo
            } else {
                $resultado = 2; // faltan reportes
            }
        } else {
            $resultado = 2; // ninguna fue planificada a tiempo
        }

        return $resultado;
    }

}

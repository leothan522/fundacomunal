<?php

namespace App\Filament\Widgets;

use App\Filament\Schemas\DatosTrabajadorInfoList;
use App\Models\GestionHumana;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class DatosTrabajadorWidget extends Widget implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.widgets.datos-trabajador-widget';
    protected static bool $isLazy = false;
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public mixed $datosTrabajador;

    public function mount(): void
    {
        $this->datosTrabajador = GestionHumana::where('users_id', auth()->user()->id)->first();
    }

    public static function canView(): bool
    {
        return GestionHumana::where('users_id', auth()->user()->id)->exists() && !auth()->user()->hasRole(['admin', 'GESTION HUMANA']);
    }

    public function datosInfoList(Schema $schema): Schema
    {
        return $schema
            ->record($this->datosTrabajador)
            ->components([
                Section::make('Tus Datos')
                    ->dense()
                    ->gap(false)
                    ->description('Datos cargados en Gestión Humana')
                    ->schema(DatosTrabajadorInfoList::schema())
                    ->compact()
                    ->collapsible()
                    ->collapsed()
            ]);
    }

}

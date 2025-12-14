<?php

namespace App\Filament\Resources\Formacions\Pages;

use App\Filament\Resources\Formacions\FormacionResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Redirect;

class ListFormacions extends ListRecords
{
    protected static string $resource = FormacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export-data')
                ->label('Exportar DATA')
                ->color('success')
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->visible(isAdmin())
                ->schema([
                    Select::make('tipo_reporte')
                        ->label('Reporte')
                        ->options([
                            'semana-actual' => 'Semana Actual',
                            'semana-anterior' => 'Semana Anterior',
                            'semana-proxima' => 'Semana Próxima',
                            'mes-actual' => 'Mes Actual',
                            'mes-anterior' => 'Mes Anterior',
                        ])
                        ->required()
                ])
                ->action(function (array $data) {
                    return Redirect::route('descargar.formacion', $data['tipo_reporte']);
                })
                ->modalWidth(Width::Small)
                ->modalIcon(Heroicon::OutlinedDocumentArrowDown)
                ->modalDescription('El procedimiento tomará tiempo. No ejecute otras opciones hasta finalizar.'),
            CreateAction::make()
                ->label('Crear Actividad'),
        ];
    }
}

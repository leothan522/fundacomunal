<?php

namespace App\Filament\Resources\Parametros\Widgets;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class ParametrosWidget extends Widget implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.resources.parametros.widgets.parametros-widget';
    protected static bool $isLazy = false;

    public function parametrosInfolist(Schema $schema): Schema
    {
        return $schema
            ->constantState([
                'parametros' => [
                    'ejemplo_nombre' => 'valor_id = null, valor_texto = string',
                ]
            ])
            ->components([
                Section::make('Parametros manuales')
                    ->schema([
                        KeyValueEntry::make('parametros')
                            ->hiddenLabel()
                            ->keyLabel('Nombre')
                            ->valueLabel('Valores'),
                    ])
                    ->compact()
                    ->collapsible()
            ]);
    }

}

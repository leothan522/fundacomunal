<?php

namespace App\Filament\Resources\Comunas\Pages;

use App\Filament\Resources\Comunas\ComunaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComuna extends CreateRecord
{
    protected static string $resource = ComunaResource::class;
    protected static bool $canCreateAnother = false;
}

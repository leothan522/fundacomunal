<?php

namespace App\Filament\Resources\ConsejoComunals\Pages;

use App\Filament\Resources\ConsejoComunals\ConsejoComunalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateConsejoComunal extends CreateRecord
{
    protected static string $resource = ConsejoComunalResource::class;
    protected static bool $canCreateAnother = false;
}

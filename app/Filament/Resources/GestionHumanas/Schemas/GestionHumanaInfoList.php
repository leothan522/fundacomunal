<?php

namespace App\Filament\Resources\GestionHumanas\Schemas;

use App\Filament\Schemas\DatosTrabajadorInfoList;
use Filament\Schemas\Schema;

class GestionHumanaInfoList
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->dense()
            ->components(DatosTrabajadorInfoList::schema());
    }
}

<?php

namespace App\Traits;

use Filament\Support\Authorization\DenyResponse;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

trait ValidarRecord
{
    public static function getViewAuthorizationResponse(?Model $record): Response
    {
        if (!$record){
            noDisponibleNotification();
            return DenyResponse::deny('El registro no existe o fue eliminado.');
        }
        return parent::getViewAuthorizationResponse($record);
    }
}

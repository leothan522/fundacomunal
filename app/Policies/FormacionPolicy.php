<?php

namespace App\Policies;

use App\Models\Formacion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FormacionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return isAdmin() || $user->hasRole('FORMACION') || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Formacion $formacion): bool
    {
        return isAdmin() || $user->hasRole('FORMACION') || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return isAdmin() || $user->hasRole('FORMACION');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Formacion $formacion): bool
    {
        $propio = true;
        if ($user->hasPermissionTo('jefe_area')){
            $propio = $formacion->users_id == $user->id;
        }
        return (isAdmin() || $user->hasRole('FORMACION')) && $propio;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Formacion $formacion): bool
    {
        $propio = true;
        if ($user->hasPermissionTo('jefe_area')){
            $propio = $formacion->users_id == $user->id;
        }
        return (isAdmin() || $user->hasRole('FORMACION')) && $propio;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Formacion $formacion): bool
    {
        return isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Formacion $formacion): bool
    {
        return isAdmin();
    }
}

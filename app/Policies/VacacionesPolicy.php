<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacaciones;
use Illuminate\Auth\Access\Response;

class VacacionesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return isAdmin() || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vacaciones $vacaciones): bool
    {
        return isAdmin() || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return isAdmin() || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vacaciones $vacaciones): bool
    {
        return (isAdmin() || $user->hasRole('GESTION HUMANA')) && !$vacaciones->deleted_at;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vacaciones $vacaciones): bool
    {
        return isAdmin() || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vacaciones $vacaciones): bool
    {
        return isAdmin() || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vacaciones $vacaciones): bool
    {
        return isAdmin() || $user->hasRole('GESTION HUMANA');
    }
}

<?php

namespace App\Policies;

use App\Models\Fortalecimiento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FortalecimientoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return isAdmin() || $user->hasRole('FORTALECIMIENTO') || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fortalecimiento $fortalecimiento): bool
    {
        return isAdmin() || $user->hasRole('FORTALECIMIENTO') || $user->hasRole('GESTION HUMANA');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return isAdmin() || $user->hasRole('FORTALECIMIENTO');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fortalecimiento $fortalecimiento): bool
    {
        return isAdmin() || $user->hasRole('FORTALECIMIENTO');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fortalecimiento $fortalecimiento): bool
    {
        return isAdmin() || $user->hasRole('FORTALECIMIENTO');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fortalecimiento $fortalecimiento): bool
    {
        return isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fortalecimiento $fortalecimiento): bool
    {
        return isAdmin();
    }
}

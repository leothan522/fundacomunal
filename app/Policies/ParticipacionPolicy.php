<?php

namespace App\Policies;

use App\Models\Participacion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ParticipacionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return isAdmin() || $user->hasRole('PARTICIPACION');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Participacion $participacion): bool
    {
        return isAdmin() || $user->hasRole('PARTICIPACION');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return isAdmin() || $user->hasRole('PARTICIPACION');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Participacion $participacion): bool
    {
        return isAdmin() || $user->hasRole('PARTICIPACION');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Participacion $participacion): bool
    {
        return isAdmin() || $user->hasRole('PARTICIPACION');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Participacion $participacion): bool
    {
        return isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Participacion $participacion): bool
    {
        return isAdmin();
    }
}

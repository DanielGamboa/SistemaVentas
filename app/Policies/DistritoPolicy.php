<?php

namespace App\Policies;

use App\Models\Distrito;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DistritoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasRole('Ver distritos') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Distrito $distrito): bool
    {
        //
        return $user->hasRole('Ver distritos') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Distrito $distrito): bool
    {
        //
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Distrito $distrito): bool
    {
        //
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Distrito $distrito): bool
    {
        //
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Distrito $distrito): bool
    {
        //
        return false;
    }
}

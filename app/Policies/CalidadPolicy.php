<?php

namespace App\Policies;

use App\Models\Calidad;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CalidadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Return true if the user has the role 'Ver calidad' or if the user is Daniel Gamboa
        return $user->hasRole('Ver calidad') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Calidad $calidad): bool
    {
        // Return true if the user has the role 'Ver calidad' or if the user is Daniel Gamboa
        return $user->hasRole('Ver calidad') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Return true if the user has the role 'Crear calidad' or if the user is Daniel Gamboa
        return $user->hasRole('Crear calidad') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Calidad $calidad): bool
    {
        //
        return $user->hasRole('Editar calidad') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Calidad $calidad): bool
    {
        //
        return $user->hasRole('Borrar calidad') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Calidad $calidad): bool
    {
        //
        return $user->hasRole('Restaurar calidad') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Calidad $calidad): bool
    {
        //
        return $user->hasRole('Eliminar calidad') || $user->email === 'dgamboa@test.com';
    }
}

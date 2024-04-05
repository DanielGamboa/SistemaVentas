<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasRole('Ver cliente') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cliente $cliente): bool
    {
        //
        return $user->hasRole('Ver cliente') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasRole('Crear cliente') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cliente $cliente): bool
    {
        //
        return $user->hasRole('Editar cliente') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cliente $cliente): bool
    {
        //
        return $user->hasRole('Borrar cliente') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cliente $cliente): bool
    {
        //
        return $user->hasRole('Restaurar cliente') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cliente $cliente): bool
    {
        //
        return $user->hasRole('Eliminar cliente') || $user->email === 'dgamboa@test.com';
    }

    /**
     * The export and import buttons are controlled from the User policy
     * 
     */

    public function exportClientes(User $user): bool
    {
        //
        return $user->hasRole('Exportar cliente') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can import the model.
     */
    public function importClientes(User $user): bool
    {
        //
        // return true;
        return $user->hasRole('Importar cliente') || $user->email === 'dgamboa@test.com';
    }
}

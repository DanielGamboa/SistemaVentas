<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Check if user has the role admin
        // return $user->hasRole('admin');
        // Check if user has role as an array
        // return $user->hasRole(['admin', 'user', 'editor', 'moderator']);
        // Check for specific user
        // Check if user has permission or email
        return $user->hasRole('Ver usuarios') || $user->email === 'dgamboa@test.com';
        // return $user->email === 'dgamboa@test.com';

        // Check if user has permission
        // text must match the permission name
        // prefered 
        // return $user->can('viewAny users');

        // return true if user dgamboa@test.com 

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        //
        return $user->hasRole('Ver usuarios') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasRole('Crear usuarios') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        //
        return $user->hasRole('Editar usuarios') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        //
        return $user->hasRole('Borrar usuarios') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
        return $user->hasRole('Restaurar usuarios') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
        return $user->hasRole('Eliminar usuarios') || $user->email === 'dgamboa@test.com';
    }

    public function exportUsers(User $user)
    {
        return $user->hasRole('Exportar usuarios') || $user->email === 'dgamboa@test.com';
    }

    public function importUsers(User $user)
    {
        return $user->hasRole('Importar usuarios') || $user->email === 'dgamboa@test.com';  
    }

    public function exportClientes(User $user): bool
    {
        //
        return $user->hasRole('Exportar cliente') || $user->email === 'dgamboa@test.com';
    }

    public function importClientes(User $user): bool
    {
        //
        // return true;
        return $user->hasRole('Importar cliente') || $user->email === 'dgamboa@test.com';
    }

    // User policy trashFilter for Calidad Resource:
    public function trashFilter(User $user): bool
    {
        return $user->hasRole('Filtro de papelera para calidad')|| $user->email === 'dgamboa@test.com';
    }

    // User policy createCalidad for Calidad Resource:
    public function createCalidad(User $user): bool
    {
        return $user->hasRole('Crear calidad') || $user->email === 'dgamboa@test.com';
    }
}

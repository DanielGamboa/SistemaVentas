<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VentaLinea;
use Illuminate\Auth\Access\Response;

class VentaLineaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // View any VentaLinea, if has "ver venta linea" permission
        // View only own VentaLinea, if has "ver venta linea propia" permission
        // View any VentaLinea, if email is "dgamboa@test.com"
        return $user->hasPermissionTo('Ver venta línea') || 
                $user->hasPermissionTo('Ver venta línea propia') ||
                $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VentaLinea $ventaLinea): bool
    {
        // View any VentaLinea, if has "ver venta linea" permission
        // View only own VentaLinea, if has "ver venta linea propia" permission
        // View any VentaLinea, if email is "dgamboa@test.com"
        return $user->hasPermissionTo('Ver venta línea') || 
                $user->hasPermissionTo('Ver venta línea propia') ||
                $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Crear venta línea
        return $user->hasPermissionTo('Crear venta línea') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VentaLinea $ventaLinea): bool
    {
        // Editar venta línea
        return $user->hasPermissionTo('Editar venta línea') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VentaLinea $ventaLinea): bool
    {
        // Borrar venta línea
        return $user->hasPermissionTo('Borrar venta línea') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VentaLinea $ventaLinea): bool
    {
        // Restaurar venta línea
        return $user->hasPermissionTo('Restaurar venta línea') || $user->email === 'dgamboa@test.com';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VentaLinea $ventaLinea): bool
    {
        // Eliminar venta línea
        return $user->hasPermissionTo('Eliminar venta línea') || $user->email === 'dgamboa@test.com';
        
    }
}

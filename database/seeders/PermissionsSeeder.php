<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make seeder for Spatie permissions
        DB::table('permissions')->insert([
            // Users Model Policy
            ['name' => 'Ver usuarios', 'guard_name' => 'web'],
            ['name' => 'Crear usuarios', 'guard_name' => 'web'],
            ['name' => 'Editar usuarios', 'guard_name' => 'web'],
            ['name' => 'Borrar usuarios', 'guard_name' => 'web'],
            ['name' => 'Restaurar usuarios', 'guard_name' => 'web'],
            ['name' => 'Eliminar usuarios', 'guard_name' => 'web'],
            ['name' => 'Exportar usuarios', 'guard_name' => 'web'],
            ['name' => 'Importar usuarios', 'guard_name' => 'web'],
            // Roles Model Policy
            ['name' => 'Ver roles', 'guard_name' => 'web'],
            ['name' => 'Crear roles', 'guard_name' => 'web'],
            ['name' => 'Editar roles', 'guard_name' => 'web'],
            ['name' => 'Borrar roles', 'guard_name' => 'web'],
            ['name' => 'Restaurar roles', 'guard_name' => 'web'],
            ['name' => 'Eliminar roles', 'guard_name' => 'web'],
            
            // Permissions Model Policy
            //  Commented out because permissions will only be managed by Daniel Gamboa
            // ['name' => 'Ver permisos', 'guard_name' => 'web'],
            // ['name' => 'Crear permisos', 'guard_name' => 'web'],
            // ['name' => 'Editar permisos', 'guard_name' => 'web'],
            // ['name' => 'Borrar permisos', 'guard_name' => 'web'],
            // ['name' => 'Restaurar permisos', 'guard_name' => 'web'],
            // ['name' => 'Eliminar permisos', 'guard_name' => 'web'],
            
            // Provincia Model Policy
            ['name' => 'Ver provincias', 'guard_name' => 'web'],

            // Cantone Model Policy
            ['name' => 'Ver cantones', 'guard_name' => 'web'],

            // Distrito Model Policy
            ['name' => 'Ver distritos', 'guard_name' => 'web'],
            
            // Cliente Model Policy
            ['name' => 'Ver cliente', 'guard_name' => 'web'],
            ['name' => 'Crear cliente', 'guard_name' => 'web'],
            ['name' => 'Editar cliente', 'guard_name' => 'web'],
            ['name' => 'Borrar cliente', 'guard_name' => 'web'],
            ['name' => 'Restaurar cliente', 'guard_name' => 'web'],
            ['name' => 'Eliminar cliente', 'guard_name' => 'web'],
            // This policy is for Client model but its defined under UserPolicy
            ['name' => 'Exportar cliente', 'guard_name' => 'web'],
            ['name' => 'Importar cliente', 'guard_name' => 'web'],

            // Calidad Model Policy
            ['name' => 'Ver calidad', 'guard_name' => 'web'],
            ['name' => 'Calidad ver todos', 'guard_name' => 'web'],
            ['name' => 'Crear calidad', 'guard_name' => 'web'],
            ['name' => 'Editar calidad', 'guard_name' => 'web'],
            ['name' => 'Borrar calidad', 'guard_name' => 'web'],
            ['name' => 'Restaurar calidad', 'guard_name' => 'web'],
            ['name' => 'Eliminar calidad', 'guard_name' => 'web'],

            // This policy is for Calidad model but its defined under UserPolicy
            ['name' => 'Filtro de papelera para calidad', 'guard_name' => 'web'],
            // ['name' => 'createCalidad', 'guard_name' => 'web'],

            // VentaLinea Policy
            ['name' => 'Ver venta línea', 'guard_name' => 'web'],
            ['name' => 'Ver venta línea propia', 'guard_name' => 'web'],
            ['name' => 'Crear venta línea', 'guard_name' => 'web'],
            ['name' => 'Editar venta línea', 'guard_name' => 'web'],
            ['name' => 'Borrar venta línea', 'guard_name' => 'web'],
            ['name' => 'Restaurar venta línea', 'guard_name' => 'web'],
            ['name' => 'Eliminar venta línea', 'guard_name' => 'web'],
            ['name' => 'Exportar venta línea', 'guard_name' => 'web'],
            ['name' => 'Importar venta línea', 'guard_name' => 'web'],
            
        ]);
    }
}

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
            ['name' => 'Ver usuarios', 'gard_name' => 'web'],
            ['name' => 'Crear usuarios', 'gard_name' => 'web'],
            ['name' => 'Editar usuarios', 'gard_name' => 'web'],
            ['name' => 'Borrar usuarios', 'gard_name' => 'web'],
            ['name' => 'Restaurar usuarios', 'gard_name' => 'web'],
            ['name' => 'Eliminar usuarios', 'gard_name' => 'web'],
            ['name' => 'Exportar usuarios', 'gard_name' => 'web'],
            ['name' => 'Importar usuarios', 'gard_name' => 'web'],
            // Roles Model Policy
            ['name' => 'Ver roles', 'gard_name' => 'web'],
            ['name' => 'Crear roles', 'gard_name' => 'web'],
            ['name' => 'Editar roles', 'gard_name' => 'web'],
            ['name' => 'Borrar roles', 'gard_name' => 'web'],
            ['name' => 'Restaurar roles', 'gard_name' => 'web'],
            ['name' => 'Eliminar roles', 'gard_name' => 'web'],
            
            // Permissions Model Policy
            //  Commented out because permissions will only be managed by Daniel Gamboa
            // ['name' => 'Ver permisos', 'gard_name' => 'web'],
            // ['name' => 'Crear permisos', 'gard_name' => 'web'],
            // ['name' => 'Editar permisos', 'gard_name' => 'web'],
            // ['name' => 'Borrar permisos', 'gard_name' => 'web'],
            // ['name' => 'Restaurar permisos', 'gard_name' => 'web'],
            // ['name' => 'Eliminar permisos', 'gard_name' => 'web'],
            
            // Provincia Model Policy
            ['name' => 'Ver provincias', 'gard_name' => 'web'],

            // Cantone Model Policy
            ['name' => 'Ver cantones', 'gard_name' => 'web'],
            
            // Cliente Model Policy
            ['name' => 'Ver cliente', 'gard_name' => 'web'],
            ['name' => 'Crear cliente', 'gard_name' => 'web'],
            ['name' => 'Editar cliente', 'gard_name' => 'web'],
            ['name' => 'Borrar cliente', 'gard_name' => 'web'],
            ['name' => 'Restaurar cliente', 'gard_name' => 'web'],
            ['name' => 'Eliminar cliente', 'gard_name' => 'web'],
            ['name' => 'Exportar cliente', 'gard_name' => 'web'],
            ['name' => 'Importar cliente', 'gard_name' => 'web'],
            


        ]);
    }
}

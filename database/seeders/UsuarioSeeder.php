<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test User Daniel Gamboa
        DB::table('users')->insert([
            'id' => '1',
            'name' => 'Daniel Gamboa',
            'cedula' => '19560922',
            'usuario' => 'dgamboa',
            'tlf' => '60013696',
            'email' => 'dgamboa@test.com',
            'fecha_ingreso' => now(),
            'estado' => 'Activo',
            'role' => 'Administrador',
            'password' => Hash::make('Nashoba144'),
        ]);

        DB::table('users')->insert([
            [
                'id' => '2',
                'name' => 'Jorge Gamboa',
                'cedula' => '12345678',
                'usuario' => 'jgamboa',
                'tlf' => '4143320326',
                'email' => 'jgamboa@test.com',
                'fecha_ingreso' => now(),
                'estado' => 'Activo',
                'role' => 'Administrador',
                'password' => Hash::make('Nashoba144'),
            ],
            [
                'id' => '3',
                'name' => 'Clara Gamboa',
                'cedula' => '532456785',
                'usuario' => 'cgamboa',
                'tlf' => '9783606019',
                'email' => 'cgamboa@test.com',
                'fecha_ingreso' => now(),
                'estado' => 'Activo',
                'role' => 'Administrador',
                'password' => Hash::make('Nashoba144'),
            ],
        ]);
    }
}

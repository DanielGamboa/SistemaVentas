<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //provincias
        DB::table('provincias')->insert([
            'id' => '1',
            'provincia' => 'San Jose',
        ]);

        DB::table('provincias')->insert([
            'id' => '2',
            'provincia' => 'Heredia',
        ]);

        DB::table('provincias')->insert([
            'id' => '3',
            'provincia' => 'Alajuela',
        ]);

        DB::table('provincias')->insert([
            'id' => '4',
            'provincia' => 'Guanacaste',
        ]);

        DB::table('provincias')->insert([
            'id' => '5',
            'provincia' => 'Puntarena',
        ]);

        DB::table('provincias')->insert([
            'id' => '6',
            'provincia' => 'Limón',
        ]);

        DB::table('provincias')->insert([
            'id' => '7',
            'provincia' => 'Cartago',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelefonosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Populate Telefonos Moviles in DB
        $numbers = [];
        $startNumber = 60000000;
        $endNumber = 60010000;
        // For production uncoment this line of code in order to generate the full list
        //$endNumber = 89999999;
        
        for ($i = $startNumber; $i <= $endNumber; $i++) {
            $numbers[] = ['telefono' => $i];
        }
        
        DB::table('telefonos')->insert($numbers);
    }
}

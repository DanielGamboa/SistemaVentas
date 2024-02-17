<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstatusCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  Seeder for estatus_count table, we are going to populate the "estatus" column with the values from the EstatusVentaLineaEnum
        //  and the count column will default to 0
        // we will populate with raw sql insert statements
        $estatus = \App\Enums\EstatusVentaLineaEnum::toArray();
        $inserts = [];
        foreach ($estatus as $estatus) {
            $inserts[] = "('" . $estatus . "', 0, 0, 0, NOW(), NOW())";
            };
        $inserts = implode(',', $inserts);
        \Illuminate\Support\Facades\DB::insert("INSERT INTO estatus_count (estatus, count, count_week, count_month, created_at, updated_at) VALUES " . $inserts);
        
    }
}
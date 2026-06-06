<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapFacilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('map_facilities')->insert([
            [
                'store_id' => 'store-101',
                'name' => 'KTCカフェ',
                'type' => 'booth',
                'floor' => 1,
                'x' => 120,
                'y' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 'store-102',
                'name' => '焼きそば',
                'type' => 'food',
                'floor' => 1,
                'x' => 60,
                'y' => 140,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

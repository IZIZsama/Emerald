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
                'store_id' => 1,
                'name' => 'VRお化け屋敷',
                'type' => 'booth',
                'floor' => 1,
                'x' => 120,
                'y' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 2,
                'name' => 'カフェ休憩所',
                'type' => 'food',
                'floor' => 1,
                'x' => 60,
                'y' => 140,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 3,
                'name' => 'ARゲームコーナー',
                'type' => 'booth',
                'floor' => 2,
                'x' => 200,
                'y' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
                [
                'store_id' => 4,
                'name' => 'グッズ販売',
                'type' => 'shop',
                'floor' => 2,
                'x' => 150,
                'y' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

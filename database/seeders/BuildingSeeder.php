<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        // Get first facility ID
        $facilityId = DB::table('facilities')->first()->id;
        
        // Get first owner ID
        $ownerId = DB::table('users')->first()->id; // assuming first user is an owner

        $buildings = [
            [
                'facility_id' => $facilityId,
                'owner_id' => $ownerId,
                'Number_of_floors' => '5',
                'Number_of_Apartments' => '20',
                'Office_ratio' => '30%',
                'image' => 'buildings/building1.jpg',
                'video' => 'buildings/building1.mp4',
                'image_gallery' => json_encode(['gallery1.jpg', 'gallery2.jpg']),
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'google_maps_url' => 'https://goo.gl/maps/example1',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'facility_id' => $facilityId,
                'owner_id' => $ownerId,
                'Number_of_floors' => '10',
                'Number_of_Apartments' => '40',
                'Office_ratio' => '40%',
                'image' => 'buildings/building2.jpg',
                'video' => 'buildings/building2.mp4',
                'image_gallery' => json_encode(['gallery3.jpg', 'gallery4.jpg']),
                'latitude' => 24.7137,
                'longitude' => 46.6754,
                'google_maps_url' => 'https://goo.gl/maps/example2',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($buildings as $index => $building) {
            $buildingId = DB::table('buildings')->insertGetId($building);
            
            // Add translations
            DB::table('building_translations')->insert([
                [
                    'building_id' => $buildingId,
                    'locale' => 'ar',
                    'name' => 'برج ' . ($index + 1),
                    'notes' => 'ملاحظات عن البرج رقم ' . ($index + 1),
                    'rules' => 'قواعد وأنظمة البرج رقم ' . ($index + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'building_id' => $buildingId,
                    'locale' => 'en',
                    'name' => 'Tower ' . ($index + 1),
                    'notes' => 'Notes about Tower ' . ($index + 1),
                    'rules' => 'Rules and regulations for Tower ' . ($index + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}

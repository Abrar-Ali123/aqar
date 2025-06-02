<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'icon' => 'fas fa-bed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'fas fa-bath',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'fas fa-car',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'fas fa-swimming-pool',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($features as $index => $feature) {
            $featureId = DB::table('features')->insertGetId($feature);
            
            // Add translations
            DB::table('feature_translations')->insert([
                [
                    'feature_id' => $featureId,
                    'locale' => 'ar',
                    'name' => match($index) {
                        0 => 'غرف النوم',
                        1 => 'دورات المياه',
                        2 => 'مواقف السيارات',
                        3 => 'مسبح',
                        default => 'ميزة ' . ($index + 1),
                    },
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'feature_id' => $featureId,
                    'locale' => 'en',
                    'name' => match($index) {
                        0 => 'Bedrooms',
                        1 => 'Bathrooms',
                        2 => 'Parking',
                        3 => 'Swimming Pool',
                        default => 'Feature ' . ($index + 1),
                    },
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}

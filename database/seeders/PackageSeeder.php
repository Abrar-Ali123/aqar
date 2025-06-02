<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'price' => 0.00,
                'duration_in_days' => 30,
                'max_products' => 5,
                'max_features' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 199.99,
                'duration_in_days' => 90,
                'max_products' => 20,
                'max_features' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 499.99,
                'duration_in_days' => 365,
                'max_products' => -1, // غير محدود
                'max_features' => -1, // غير محدود
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($packages as $index => $package) {
            $packageId = DB::table('packages')->insertGetId($package);
            
            // Add translations
            DB::table('package_translations')->insert([
                [
                    'package_id' => $packageId,
                    'locale' => 'ar',
                    'name' => match($index) {
                        0 => 'الباقة المجانية',
                        1 => 'الباقة الفضية',
                        2 => 'الباقة الذهبية',
                        default => 'باقة ' . ($index + 1),
                    },
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'package_id' => $packageId,
                    'locale' => 'en',
                    'name' => match($index) {
                        0 => 'Free Package',
                        1 => 'Silver Package',
                        2 => 'Gold Package',
                        default => 'Package ' . ($index + 1),
                    },
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'الرياض'],
            ['name' => 'جدة'],
            ['name' => 'مكة المكرمة'],
            ['name' => 'المدينة المنورة'],
            ['name' => 'الدمام'],
            ['name' => 'الخبر'],
            ['name' => 'تبوك'],
            ['name' => 'أبها'],
            ['name' => 'القصيم'],
            ['name' => 'حائل'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}

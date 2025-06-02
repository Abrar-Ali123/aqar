<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $facility = Facility::first();
        $category = Category::first();
        $user = User::first();

        if (!$facility || !$category || !$user) {
            $this->command->error('Required data not found.');
            return;
        }

        $this->command->info('Adding products...');

        try {
            $productId = DB::table('products')->insertGetId([
                'category_id' => $category->id,
                'facility_id' => $facility->id,
                'owner_user_id' => $user->id,
                'seller_user_id' => $user->id,
                'type' => 'sale',
                'price' => 850000.00,
                'is_active' => true,
                'is_featured' => true,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'google_maps_url' => 'https://goo.gl/maps/example1',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('product_translations')->insert([
                [
                    'product_id' => $productId,
                    'locale' => 'ar',
                    'name' => 'شقة سكنية',
                    'description' => 'شقة حديثة في موقع متميز',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'product_id' => $productId,
                    'locale' => 'en',
                    'name' => 'Residential Apartment',
                    'description' => 'Modern apartment in a prime location',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);

            $this->command->info('Added product successfully');
        } catch (\Exception $e) {
            $this->command->error('Error adding product: ' . $e->getMessage());
        }
    }
}

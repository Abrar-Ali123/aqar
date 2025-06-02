<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductType;

class ProductTypeSeeder extends Seeder
{
    public function run()
    {
        // الأنواع الأساسية
        $types = [
            ['key' => 'sale'],
            ['key' => 'rent'],
            ['key' => 'subscription'],
        ];
        foreach ($types as $type) {
            $productType = ProductType::create(['key' => $type['key']]);
            // ترجمات افتراضية
            $productType->translateOrNew('ar')->label = match($type['key']) {
                'sale' => 'بيع',
                'rent' => 'إيجار',
                'subscription' => 'اشتراك',
                default => $type['key'],
            };
            $productType->translateOrNew('en')->label = match($type['key']) {
                'sale' => 'Sale',
                'rent' => 'Rent',
                'subscription' => 'Subscription',
                default => ucfirst($type['key']),
            };
            $productType->save();
        }
    }
}

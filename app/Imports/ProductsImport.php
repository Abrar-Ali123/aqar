<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Facility;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $product = Product::create([
            'sku' => $row['sku'],
            'price' => $row['price'],
            'type' => $row['type'],
            'category_id' => $this->getCategoryId($row['category']),
            'facility_id' => $this->getFacilityId($row['facility']),
            'quantity' => $row['quantity'] ?? 0,
            'low_stock_threshold' => $row['low_stock_threshold'] ?? 0,
            'is_active' => $row['is_active'] === 'نعم'
        ]);

        // إضافة الترجمات
        $product->translations()->createMany([
            [
                'locale' => 'ar',
                'name' => $row['name_ar'],
                'description' => $row['description_ar']
            ],
            [
                'locale' => 'en',
                'name' => $row['name_en'],
                'description' => $row['description_en']
            ]
        ]);

        return $product;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:physical,digital,service',
            'category' => 'required|exists:categories,name',
            'facility' => 'nullable|exists:facilities,name',
            'name_ar' => 'required|string|max:255',
            'description_ar' => 'required|string'
        ];
    }

    private function getCategoryId($name)
    {
        return Category::where('name', $name)->value('id');
    }

    private function getFacilityId($name)
    {
        return $name ? Facility::where('name', $name)->value('id') : null;
    }
}

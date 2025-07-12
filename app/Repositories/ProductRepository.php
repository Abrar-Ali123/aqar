<?php

namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getFeatured(int $limit = 12): Collection
    {
        return Product::with([
            'translations',
            'facility.translations',
            'category.translations'
        ])
        ->latest()
        ->take($limit)
        ->get();
    }
}

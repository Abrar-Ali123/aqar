<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     */
    public function creating(Product $product): void
    {
        if (empty($product->sku)) {
            $product->sku = $this->generateUniqueSku();
        }
    }

    /**
     * Generate a unique SKU for the product.
     *
     * @return string
     */
    private function generateUniqueSku(): string
    {
        do {
            $prefix = 'PRD';
            $timestamp = now()->format('ymd');
            $random = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $sku = "{$prefix}-{$timestamp}-{$random}";
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}

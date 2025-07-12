<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function createProduct(Request $request): Product
    {
        return DB::transaction(function () use ($request) {
            $product = new Product();
            $this->fillProductData($product, $request);

            if ($request->hasFile('thumbnail')) {
                $product->thumbnail = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            $product->save();

            $this->syncTranslations($product, $request->input('translations', []));
            $this->syncMedia($product, $request->file('media', []));
            $this->syncAttributes($product, $request->input('attributes', []));

            return $product;
        });
    }

    public function updateProduct(Product $product, Request $request): Product
    {
        return DB::transaction(function () use ($product, $request) {
            $this->fillProductData($product, $request);

            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if it exists
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $product->thumbnail = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            $product->save();

            $this->syncTranslations($product, $request->input('translations', []));
            $this->syncMedia($product, $request->file('media', [])); // This needs careful implementation for updates
            $this->syncAttributes($product, $request->input('attributes', []));

            return $product;
        });
    }

    private function fillProductData(Product $product, Request $request): void
    {
        $product->fill($request->only([
            'type',
            'price',
            'category_id',
            'facility_id',
            'latitude',
            'longitude',
            'google_maps_url'
        ]));

        $product->is_active = $request->boolean('is_active', true);
        if ($product->isDirty() || !$product->exists) {
            $product->owner_user_id = $product->owner_user_id ?? auth()->id();
            $product->seller_user_id = $request->seller_user_id ?? auth()->id();
        }
    }

    private function syncTranslations(Product $product, array $translationsData): void
    {
        $product->translations()->delete();
        foreach ($translationsData as $locale => $data) {
            if (!empty($data['name'])) {
                $product->translations()->create(['locale' => $locale] + $data);
            }
        }
    }

    private function syncMedia(Product $product, array $mediaFiles): void
    {
        // Note: This is a simple implementation. For a real-world app,
        // you might want to handle deleting old files, updating existing ones, etc.
        if (!empty($mediaFiles)) {
            $mediaPaths = $product->media ?? [];
            foreach ($mediaFiles as $file) {
                $path = $file->store('products/media', 'public');
                $mediaPaths[] = ['path' => $path, 'name' => $file->getClientOriginalName()];
            }
            $product->media = $mediaPaths;
        }
    }

    private function syncAttributes(Product $product, array $attributesData): void
    {
        $product->attributeValues()->delete();
        foreach ($attributesData as $attributeId => $value) {
            if (!is_null($value)) {
                 $attribute = \App\Models\Attribute::find($attributeId);
                 if($attribute){
                    $processedValue = $this->processAttributeValue($attribute, $value);
                    $product->attributeValues()->create([
                        'attribute_id' => $attributeId,
                        'value' => $processedValue
                    ]);
                 }
            }
        }
    }

    protected function processAttributeValue($attribute, $value)
    {
        switch ($attribute->type) {
            case 'number':
                return floatval($value);
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'date':
                return date('Y-m-d', strtotime($value));
            default:
                return $value;
        }
    }

    public function deleteProduct(Product $product): void
    {
        DB::transaction(function () use ($product) {
            // Delete associated files from storage
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            
            if (!empty($product->media)) {
                foreach ($product->media as $media) {
                    if (isset($media['path'])) {
                         Storage::disk('public')->delete($media['path']);
                    }
                }
            }

            // Delete the product record. Associated records should be handled by DB constraints (cascade delete).
            $product->delete();
        });
    }
}

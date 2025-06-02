<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductComparison;
use App\Models\ProductArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Notifications\ProductUpdateNotification;
use App\Models\Attribute;
use App\Models\Location;
use App\Models\InventoryMovement;
use App\Models\Unit;

class ProductFeatureController extends Controller
{
    // معاينة سريعة للمنتج
    public function quickView(Product $product)
    {
        return response()->json([
            'product' => $product->load(['translations', 'images', 'category', 'attributeValues']),
            'view' => view('products.partials.quick-view', compact('product'))->render()
        ]);
    }

    // مقارنة المنتجات
    public function compare(Request $request)
    {
        $productIds = $request->get('products', []);
        $products = Product::whereIn('id', $productIds)
            ->with(['translations', 'category', 'attributeValues'])
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'view' => view('products.partials.comparison', compact('products'))->render()
            ]);
        }

        return view('products.compare', compact('products'));
    }

    // تصدير المنتجات
    public function export(Request $request)
    {
        return Excel::download(new ProductsExport($request), 'products.xlsx');
    }

    // استيراد المنتجات
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return back()->with('success', 'تم استيراد المنتجات بنجاح');
    }

    // إضافة تقييم
    public function addReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000'
        ]);

        $review = $product->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'review' => $review->load('user'),
            'averageRating' => $product->reviews()->avg('rating')
        ]);
    }

    // إضافة تعليق
    public function addComment(Request $request, Product $product)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $comment = $product->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user')
        ]);
    }

    // أرشفة منتج
    public function archive(Product $product)
    {
        $product->update(['is_archived' => true]);
        ProductArchive::create([
            'product_id' => $product->id,
            'archived_by' => Auth::id(),
            'reason' => request('reason')
        ]);

        return back()->with('success', 'تم أرشفة المنتج بنجاح');
    }

    // نسخ منتج
    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->sku = $product->sku . '-copy-' . time();
        $newProduct->is_active = false;
        $newProduct->save();

        // نسخ الترجمات
        foreach ($product->translations as $translation) {
            $newTranslation = $translation->replicate();
            $newTranslation->product_id = $newProduct->id;
            $newTranslation->save();
        }

        // نسخ الصور
        foreach ($product->images as $image) {
            $newPath = str_replace($product->id, $newProduct->id, $image->path);
            Storage::copy('public/' . $image->path, 'public/' . $newPath);
            $newProduct->images()->create(['path' => $newPath]);
        }

        // نسخ السمات
        foreach ($product->attributeValues as $value) {
            $newProduct->attributeValues()->create($value->toArray());
        }

        return redirect()->route('admin.products.edit', $newProduct)
            ->with('success', 'تم نسخ المنتج بنجاح');
    }

    // إدارة المخزون
    public function updateStock(Request $request, Product $product)
    {
        // التحقق من دعم المنشأة لإدارة المخزون
        if (!$product->facility?->supportsInventory()) {
            return response()->json([
                'message' => 'This facility does not support inventory management'
            ], 400);
        }

        // التحقق من تفعيل تتبع المخزون للمنتج
        if (!$product->tracksInventory()) {
            return response()->json([
                'message' => 'Inventory tracking is not enabled for this product'
            ], 400);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $product->updateStock($validated['quantity']);

        return response()->json([
            'message' => 'Stock updated successfully',
            'data' => [
                'quantity' => $product->quantity,
                'has_stock' => $product->hasStock(),
                'low_stock' => $product->hasLowStock()
            ]
        ]);
    }

    public function toggleInventoryTracking(Request $request, Product $product)
    {
        // التحقق من دعم المنشأة لإدارة المخزون
        if (!$product->facility?->supportsInventory()) {
            return response()->json([
                'message' => 'This facility does not support inventory management'
            ], 400);
        }

        $validated = $request->validate([
            'track_inventory' => 'required|boolean',
            'quantity' => 'required_if:track_inventory,true|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:1'
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Inventory tracking settings updated successfully',
            'data' => [
                'track_inventory' => $product->track_inventory,
                'quantity' => $product->quantity,
                'low_stock_threshold' => $product->low_stock_threshold
            ]
        ]);
    }

    public function updateInventory(Request $request, Product $product)
    {
        $validated = $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date',
            'batch_number' => 'nullable|string',
            'metadata' => 'nullable|array'
        ]);

        $attribute = Attribute::findOrFail($validated['attribute_id']);

        // التحقق من أن السمة تدعم إدارة المخزون
        if (!$attribute->supportsInventory()) {
            return response()->json([
                'message' => 'This attribute does not support inventory management'
            ], 400);
        }

        // تحديث أو إنشاء قيمة المخزون
        $inventory = $product->inventoryValues()
            ->updateOrCreate(
                [
                    'attribute_id' => $attribute->id,
                    'attribute_value' => $validated['attribute_value']
                ],
                [
                    'quantity' => $validated['quantity'],
                    'low_stock_threshold' => $validated['low_stock_threshold'],
                    'expiry_date' => $validated['expiry_date'],
                    'batch_number' => $validated['batch_number'],
                    'metadata' => $validated['metadata']
                ]
            );

        return response()->json([
            'message' => 'Inventory updated successfully',
            'data' => [
                'inventory' => $inventory,
                'has_stock' => $inventory->hasStock(),
                'low_stock' => $inventory->hasLowStock(),
                'is_expired' => $inventory->isExpired(),
                'is_near_expiry' => $inventory->isNearExpiry()
            ]
        ]);
    }

    public function getInventory(Request $request, Product $product)
    {
        $validated = $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'nullable|string'
        ]);

        $attribute = Attribute::findOrFail($validated['attribute_id']);
        $inventory = $product->getInventoryForAttribute(
            $attribute, 
            $validated['attribute_value'] ?? null
        );

        return response()->json([
            'data' => $inventory
        ]);
    }

    public function updateInventoryInLocation(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'unit_id' => 'required|exists:units,id',
            'reason' => 'required|string',
            'metadata' => 'nullable|array'
        ]);

        $location = Location::findOrFail($validated['location_id']);
        $attribute = Attribute::findOrFail($validated['attribute_id']);
        $unit = Unit::findOrFail($validated['unit_id']);

        // التحقق من أن السمة تدعم إدارة المخزون
        if (!$attribute->supportsInventory()) {
            return response()->json([
                'message' => 'This attribute does not support inventory management'
            ], 400);
        }

        $inventory = $product->inventoryValues()
            ->where('location_id', $location->id)
            ->where('attribute_id', $attribute->id)
            ->where('attribute_value', $validated['attribute_value'])
            ->first();

        if ($inventory) {
            $inventory->updateQuantityWithUnit(
                $validated['quantity'],
                $unit
            );
        } else {
            $inventory = $product->inventoryValues()->create([
                'location_id' => $location->id,
                'attribute_id' => $attribute->id,
                'attribute_value' => $validated['attribute_value'],
                'quantity' => $validated['quantity'],
                'unit_id' => $unit->id
            ]);
        }

        // تسجيل حركة المخزون
        $movement = InventoryMovement::recordMovement(
            $product,
            $attribute,
            $validated['attribute_value'],
            'adjustment',
            $validated['quantity'],
            null,
            $location,
            'adjustment',
            null,
            $validated['reason'],
            array_merge($validated['metadata'] ?? [], ['unit_id' => $unit->id])
        );

        return response()->json([
            'message' => 'Location inventory updated successfully',
            'data' => [
                'movement' => $movement,
                'current_stock' => $inventory
            ]
        ]);
    }

    public function transferInventory(Request $request, Product $product)
    {
        $validated = $request->validate([
            'from_location_id' => 'required|exists:locations,id',
            'to_location_id' => 'required|exists:locations,id|different:from_location_id',
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string',
            'quantity' => 'required|numeric|min:0.00001',
            'unit_id' => 'required|exists:units,id',
            'notes' => 'nullable|string'
        ]);

        $fromLocation = Location::findOrFail($validated['from_location_id']);
        $toLocation = Location::findOrFail($validated['to_location_id']);
        $attribute = Attribute::findOrFail($validated['attribute_id']);
        $unit = Unit::findOrFail($validated['unit_id']);

        try {
            $fromInventory = $product->inventoryValues()
                ->where('location_id', $fromLocation->id)
                ->where('attribute_id', $attribute->id)
                ->where('attribute_value', $validated['attribute_value'])
                ->first();

            if (!$fromInventory || !$fromInventory->hasAvailableQuantity($validated['quantity'], $unit)) {
                throw new \Exception('Insufficient stock in source location');
            }

            // تحويل الكمية إلى وحدة المخزون المصدر
            $quantity = $unit->convert($validated['quantity'], $fromInventory->unit);

            // تحديث المخزون في الموقع المصدر
            $fromInventory->decrement('quantity', $quantity);

            // تحديث المخزون في الموقع الهدف
            $toInventory = $product->inventoryValues()
                ->firstOrCreate([
                    'location_id' => $toLocation->id,
                    'attribute_id' => $attribute->id,
                    'attribute_value' => $validated['attribute_value']
                ], [
                    'quantity' => 0,
                    'unit_id' => $fromInventory->unit_id
                ]);

            $toInventory->increment('quantity', $quantity);

            // تسجيل حركة النقل
            $movement = InventoryMovement::recordMovement(
                $product,
                $attribute,
                $validated['attribute_value'],
                'transfer',
                $validated['quantity'],
                $fromLocation,
                $toLocation,
                null,
                null,
                $validated['notes'],
                ['unit_id' => $unit->id]
            );

            return response()->json([
                'message' => 'Stock transferred successfully',
                'data' => [
                    'movement' => $movement,
                    'from_location_stock' => $fromInventory->fresh(),
                    'to_location_stock' => $toInventory->fresh()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getInventoryMovements(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'attribute_id' => 'nullable|exists:attributes,id',
            'unit_id' => 'nullable|exists:units,id',
            'movement_type' => 'nullable|string|in:in,out,transfer,adjustment',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $query = InventoryMovement::query()
            ->where('product_id', $product->id)
            ->when($validated['location_id'] ?? null, function ($query, $locationId) {
                $query->where(function ($q) use ($locationId) {
                    $q->where('from_location_id', $locationId)
                        ->orWhere('to_location_id', $locationId);
                });
            })
            ->when($validated['attribute_id'] ?? null, function ($query, $attributeId) {
                $query->where('attribute_id', $attributeId);
            })
            ->when($validated['unit_id'] ?? null, function ($query, $unitId) {
                $query->whereJsonContains('metadata->unit_id', $unitId);
            })
            ->when($validated['movement_type'] ?? null, function ($query, $type) {
                $query->where('movement_type', $type);
            })
            ->when($validated['from_date'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($validated['to_date'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            });

        $movements = $query->paginate($validated['per_page'] ?? 15);

        return response()->json([
            'data' => $movements
        ]);
    }

    // الحصول على الوحدات المتوافقة للمنتج
    public function getCompatibleUnits(Request $request, Product $product, Attribute $attribute)
    {
        $inventory = $product->inventoryValues()
            ->where('attribute_id', $attribute->id)
            ->whereNotNull('unit_id')
            ->first();

        if (!$inventory) {
            return response()->json([
                'message' => 'No units found for this product attribute',
                'data' => []
            ]);
        }

        $units = $inventory->unit->getCompatibleUnits();

        return response()->json([
            'data' => $units
        ]);
    }
}

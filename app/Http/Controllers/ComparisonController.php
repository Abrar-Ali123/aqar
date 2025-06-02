<?php

namespace App\Http\Controllers;

use App\Models\Comparison;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ComparisonController extends TranslatableController
{
    protected $translatableFields = [
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'summary' => ['nullable', 'string'],
    ];

    /**
     * عرض صفحة المقارنة
     */
    public function index(): View
    {
        $products = auth()->user()->comparisons()
            ->with('product.category', 'product.features')
            ->get()
            ->pluck('product');

        return view('comparisons.index', compact('products'));
    }

    /**
     * إضافة منتج للمقارنة
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            auth()->user()->addToComparisons($product);

            return response()->json([
                'message' => 'تمت إضافة المنتج لقائمة المقارنة',
                'count' => auth()->user()->comparisons()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * إزالة منتج من المقارنة
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        auth()->user()->removeFromComparisons($product);

        return response()->json([
            'message' => 'تم حذف المنتج من قائمة المقارنة',
            'count' => auth()->user()->comparisons()->count()
        ]);
    }

    /**
     * مسح قائمة المقارنة
     */
    public function clear(): JsonResponse
    {
        auth()->user()->comparisons()->delete();

        return response()->json([
            'message' => 'تم مسح قائمة المقارنة'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Models\ProductType as DynamicProductType;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
        // يمكنك إضافة middleware هنا إذا احتجت
        // مثال: $this->middleware('auth')->except(['index', 'show', 'search']);
    }

    public function index(Request $request): View
    {
        $locale = app()->getLocale();

        $products = Product::filter($request->all())
            ->with([
                'facility' => function ($q) use ($locale) {
                    $q->select(['facilities.id', 'facility_translations.name'])
                        ->join('facility_translations', 'facilities.id', '=', 'facility_translations.facility_id')
                        ->where('facility_translations.locale', $locale);
                },
                'category' => function ($q) use ($locale) {
                    $q->select(['categories.id', 'category_translations.name'])
                        ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
                        ->where('category_translations.locale', $locale);
                },
                'translations' => function ($q) use ($locale) {
                    $q->where('locale', $locale);
                }
            ])
            ->latest()
            ->paginate(12);

        $categories = Category::query()
            ->select(['categories.id', 'category_translations.name'])
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', $locale)
            ->withCount('products')
            ->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'type' => $request->type,
            'search_query' => $request->search,
        ]);
    }

    public function create()
    {
        $staticTypes = collect(ProductType::cases())->map(fn($t) => [
            'key' => $t->value,
            'label' => $t->label(),
        ]);
        $dynamicTypes = DynamicProductType::all(['key', 'label']);
        $allTypes = $staticTypes->merge($dynamicTypes);
        return view('dashboard.products.create', compact('allTypes'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->createProduct($request);

        return redirect()
            ->route('products.show', ['product' => $product->id, 'locale' => app()->getLocale()])
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function show(Product $product): View
    {
        // تحميل العلاقات المطلوبة
        $product->load(['facility', 'category', 'owner', 'seller', 'attributeValues.attribute', 'translations']);

        // الحصول على المنتجات المشابهة
        $relatedProducts = Product::with(['facility', 'category'])
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where(function($query) use ($product) {
                $query->where('category_id', $product->category_id)
                      ->orWhere('type', $product->type);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $product = Product::findOrFail($id);
        $staticTypes = collect(ProductType::cases())->map(fn($t) => [
            'key' => $t->value,
            'label' => $t->label(),
        ]);
        $dynamicTypes = DynamicProductType::all(['key', 'label']);
        $allTypes = $staticTypes->merge($dynamicTypes);
        return view('dashboard.products.edit', compact('product', 'allTypes'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->updateProduct($product, $request);

        return redirect()
            ->route('products.show', ['product' => $product->id, 'locale' => app()->getLocale()])
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()
            ->route('products.index', ['locale' => app()->getLocale()])
            ->with('success', 'تم حذف المنتج بنجاح');
    }



    public function search(Request $request): View
    {
        $locale = app()->getLocale();

        $products = Product::filter($request->all())
            ->with([
                'facility' => function ($q) use ($locale) {
                    $q->select(['facilities.id', 'facility_translations.name'])
                        ->join('facility_translations', 'facilities.id', '=', 'facility_translations.facility_id')
                        ->where('facility_translations.locale', $locale);
                },
                'category' => function ($q) use ($locale) {
                    $q->select(['categories.id', 'category_translations.name'])
                        ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
                        ->where('category_translations.locale', $locale);
                },
                'translations' => function ($q) use ($locale) {
                    $q->where('locale', $locale);
                }
            ])
            ->latest()
            ->paginate(12);

        $categories = Category::query()
            ->select(['categories.id', 'category_translations.name'])
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', $locale)
            ->withCount('products')
            ->get();

        return view('products.search', [
            'products' => $products,
            'categories' => $categories,
            'search_query' => $request->q,
            'selected_category' => $request->category,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'selected_facility' => $request->facility
        ]);
    }


}

<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Models\ProductType as DynamicProductType;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct()
    {
        // لا يوجد middleware
    }

    public function index(Request $request): View
    {
        $locale = app()->getLocale();
        
        // Get products with their translations
        $query = Product::query()
            ->select('products.*')
            ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('product_translations.locale', $locale)
            ->where('products.is_active', true);

        // البحث حسب الاسم
        if ($request->filled('search')) {
            $query->where('product_translations.name', 'like', '%' . $request->search . '%');
        }

        // التصفية حسب الفئة
        if ($request->filled('category_id')) {
            $query->where('products.category_id', $request->category_id);
        }

        // Get categories with their translations
        $categories = Category::query()
            ->select([
                'categories.id',
                'category_translations.name'
            ])
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', $locale)
            ->withCount('products')
            ->get();

        // Get products with their relations
        $products = $query
            ->with([
                'facility' => function($q) use ($locale) {
                    $q->select([
                        'facilities.id',
                        'facility_translations.name'
                    ])
                    ->join('facility_translations', 'facilities.id', '=', 'facility_translations.facility_id')
                    ->where('facility_translations.locale', $locale);
                },
                'category' => function($q) use ($locale) {
                    $q->select([
                        'categories.id',
                        'category_translations.name'
                    ])
                    ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', $locale);
                },
                'translations' => function($q) use ($locale) {
                    $q->where('locale', $locale);
                }
            ])
            ->latest()
            ->paginate(12);

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

    public function store(Request $request)
    {
        $request->validate([
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'thumbnail' => 'nullable|image|max:2048',
            'media.*' => 'nullable|file|max:10240',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url'
        ]);

        $product = new Product();
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
        $product->sku = $this->generateSku();
        $product->owner_user_id = auth()->id();
        $product->seller_user_id = $request->seller_user_id ?? auth()->id();

        // معالجة الصورة الرئيسية
        if ($request->hasFile('thumbnail')) {
            $product->thumbnail = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // معالجة الوسائط
        if ($request->hasFile('media')) {
            $mediaFiles = [];
            foreach ($request->file('media') as $file) {
                $mediaFiles[] = [
                    'path' => $file->store('products/media', 'public'),
                    'type' => $file->getClientMimeType(),
                    'name' => $file->getClientOriginalName()
                ];
            }
            $product->media = $mediaFiles;
        }

        $product->save();

        // حفظ الترجمات
        foreach ($request->translations as $locale => $translation) {
            ProductTranslation::create([
                'product_id' => $product->id,
                'locale' => $locale,
                'name' => $translation['name'],
                'description' => $translation['description'] ?? null
            ]);
        }

        // حفظ قيم الخصائص
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attributeId => $value) {
                $attribute = Attribute::findOrFail($attributeId);
                $processedValue = $this->processAttributeValue($attribute, $value);
                $product->attributeValues()->create([
                    'attribute_id' => $attributeId,
                    'value' => $processedValue
                ]);
            }
        }

        return redirect()
            ->route('products.show', $product)
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
        $staticTypes = collect(ProductType::cases())->map(fn($t) => [
            'key' => $t->value,
            'label' => $t->label(),
        ]);
        $dynamicTypes = DynamicProductType::all(['key', 'label']);
        $allTypes = $staticTypes->merge($dynamicTypes);
        return view('dashboard.products.edit', compact('product', 'allTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'thumbnail' => 'nullable|image|max:2048',
            'media.*' => 'nullable|file|max:10240',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url'
        ]);

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
        $product->seller_user_id = $request->seller_user_id ?? $product->seller_user_id;

        // معالجة الصورة الرئيسية
        if ($request->hasFile('thumbnail')) {
            // حذف الصورة القديمة
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $product->thumbnail = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // معالجة الوسائط
        if ($request->hasFile('media')) {
            // حذف الوسائط القديمة
            if (!empty($product->media)) {
                foreach ($product->media as $media) {
                    Storage::disk('public')->delete($media['path']);
                }
            }
            
            $mediaFiles = [];
            foreach ($request->file('media') as $file) {
                $mediaFiles[] = [
                    'path' => $file->store('products/media', 'public'),
                    'type' => $file->getClientMimeType(),
                    'name' => $file->getClientOriginalName()
                ];
            }
            $product->media = $mediaFiles;
        }

        $product->save();

        // تحديث الترجمات
        foreach ($request->translations as $locale => $translation) {
            $product->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $translation['name'],
                    'description' => $translation['description'] ?? null
                ]
            );
        }

        // تحديث قيم الخصائص
        $product->attributeValues()->delete();
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attributeId => $value) {
                $attribute = Attribute::findOrFail($attributeId);
                $processedValue = $this->processAttributeValue($attribute, $value);
                $product->attributeValues()->create([
                    'attribute_id' => $attributeId,
                    'value' => $processedValue
                ]);
            }
        }

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        // حذف الملفات
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        
        if (!empty($product->media)) {
            foreach ($product->media as $media) {
                Storage::disk('public')->delete($media['path']);
            }
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
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

    protected function generateSku()
    {
        $prefix = 'PRD';
        $timestamp = now()->format('ymd');
        $random = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$timestamp}{$random}";
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Language;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view products')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $products = Cache::remember('products.page.' . request('page', 1), 3600, function () {
            return Product::with(['facility', 'category', 'translations'])
                ->when(request('facility_id'), function (Builder $query) {
                    $query->where('facility_id', request('facility_id'));
                })
                ->when(request('category_id'), function (Builder $query) {
                    $query->where('category_id', request('category_id'));
                })
                ->latest()
                ->paginate(15);
        });

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        if (!auth()->user()->can('create products')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $categories = Category::with('translations')->get();
        return view('admin.products.create', compact('languages', 'categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create products')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء المنتج
                $product = Product::create([
                    'category_id' => $request->category_id,
                    'facility_id' => $request->facility_id,
                    'owner_user_id' => $request->owner_user_id,
                    'seller_user_id' => $request->seller_user_id,
                    'sku' => $request->sku,
                    'type' => $request->type,
                    'price' => $request->price,
                    'is_active' => $request->boolean('is_active'),
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'google_maps_url' => $request->google_maps_url,
                    'metadata' => $request->metadata,
                ]);

                // حفظ الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    if ($name || Language::where('code', $locale)->value('is_required')) {
                        $product->translations()->create([
                            'locale' => $locale,
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                        ]);
                    }
                }

                // معالجة الصور
                if ($request->hasFile('images')) {
                    $this->handleProductImages($product, $request->file('images'));
                }

                // معالجة الملفات الرقمية
                if ($request->hasFile('digital_files')) {
                    $this->handleDigitalFiles($product, $request->file('digital_files'));
                }

                // ربط السمات
                if ($request->has('attributes')) {
                    foreach ($request->attributes as $attributeId => $value) {
                        $product->attributes()->attach($attributeId, ['value' => $value]);
                    }
                }
            });

            // مسح الكاش المتعلق بالمنتجات
            $this->clearProductsCache($product);

            return redirect()->route('admin.products.index')
                ->with('success', __('messages.product_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.product_create_error'))
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        if (!auth()->user()->can('edit products')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $product->translations->keyBy('locale');
        $categories = Category::with('translations')->get();
        
        // تحضير السمات والمميزات المحددة
        $selectedAttributes = $product->attributes->pluck('pivot.value', 'id');

        return view('admin.products.edit', compact(
            'product',
            'languages',
            'translations',
            'categories',
            'selectedAttributes'
        ));
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->can('edit products')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($product->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $product, $validated) {
                // تحديث المنتج
                $product->update([
                    'category_id' => $request->category_id,
                    'facility_id' => $request->facility_id,
                    'owner_user_id' => $request->owner_user_id,
                    'seller_user_id' => $request->seller_user_id,
                    'sku' => $request->sku,
                    'type' => $request->type,
                    'price' => $request->price,
                    'is_active' => $request->boolean('is_active'),
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'google_maps_url' => $request->google_maps_url,
                    'metadata' => $request->metadata,
                ]);

                // تحديث الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    $product->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                        ]
                    );
                }

                // معالجة الصور والملفات
                if ($request->hasFile('images')) {
                    $this->handleProductImages($product, $request->file('images'));
                }

                if ($request->hasFile('digital_files')) {
                    $this->handleDigitalFiles($product, $request->file('digital_files'));
                }

                // تحديث السمات
                $product->attributes()->detach();
                if ($request->has('attributes')) {
                    foreach ($request->attributes as $attributeId => $value) {
                        $product->attributes()->attach($attributeId, ['value' => $value]);
                    }
                }

                // تحديث البيانات الوصفية
                $product->metadata = array_merge($product->metadata ?? [], [
                    'last_updated_by' => auth()->id(),
                    'last_updated_at' => now(),
                    'update_notes' => $request->update_notes,
                ]);
                $product->save();
            });

            // مسح الكاش المتعلق بالمنتج
            $this->clearProductsCache($product);

            return redirect()->route('admin.products.index')
                ->with('success', __('messages.product_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.product_update_error'))
                ->withInput();
        }
    }

    /**
     * معالجة صور المنتج
     */
    private function handleProductImages(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $path = $image->store('products/images', 'public');
                
                // معالجة الصورة وتحسينها
                $img = Image::make(storage_path('app/public/' . $path));
                
                // تحسين جودة الصورة
                $img->encode('jpg', 80);
                
                // تغيير حجم الصورة إذا كانت كبيرة جداً
                if ($img->width() > 1920) {
                    $img->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
                
                // حفظ الصورة المحسنة
                $img->save();

                // إنشاء سجل للصورة
                $product->images()->create([
                    'path' => $path,
                    'type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                    'order' => $index,
                    'alt' => $product->name,
                    'title' => $product->name,
                    'metadata' => [
                        'width' => $img->width(),
                        'height' => $img->height(),
                        'original_name' => $image->getClientOriginalName()
                    ]
                ]);

                // تعيين الصورة الرئيسية إذا كانت الأولى
                if ($index === 0 && !$product->thumbnail) {
                    $product->update(['thumbnail' => $path]);
                }
            }
        }
    }

    /**
     * معالجة الملفات الرقمية للمنتج
     */
    private function handleDigitalFiles(Product $product, array $files): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store('products/files', 'private');

                // إنشاء سجل للملف
                $product->files()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'is_downloadable' => true,
                    'metadata' => [
                        'original_name' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension()
                    ]
                ]);
            }
        }
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->can('delete products')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        try {
            DB::transaction(function () use ($product) {
                // حذف الصور
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->path);
                }

                // حذف المنتج (سيتم حذف الترجمات والصور تلقائياً بسبب onDelete('cascade'))
                $product->delete();
            });

            // مسح الكاش المتعلق بالمنتج
            $this->clearProductsCache($product);

            return redirect()->route('admin.products.index')
                ->with('success', __('messages.product_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.product_delete_error'));
        }
    }

    /**
     * الحصول على قواعد التحقق
     */
    private function getValidationRules($productId = null): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'facility_id' => 'required|exists:facilities,id',
            'owner_user_id' => 'required|exists:users,id',
            'seller_user_id' => 'required|exists:users,id',
            'sku' => 'required|string|max:50|unique:products,sku' . ($productId ? ",{$productId}" : ''),
            'type' => 'required|in:physical,digital,service,sale,rent',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url|max:255',
            'metadata' => 'nullable|array',

            // الترجمات
            'ar.name' => 'required|string|max:255',
            'ar.description' => 'required|string',
            'en.name' => 'nullable|string|max:255',
            'en.description' => 'nullable|string',

            // الصور
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'digital_files.*' => [
                'nullable',
                'file',
                'max:10240', // 10MB
                function ($attribute, $value, $fail) {
                    $allowedMimes = [
                        'application/pdf',
                        'application/zip',
                        'application/x-zip-compressed',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ];

                    if (!in_array($value->getMimeType(), $allowedMimes)) {
                        $fail('نوع الملف غير مسموح به.');
                    }
                }
            ],

            // السمات
            'attributes' => 'array',
            'attributes.*.attribute_id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string|max:255',
        ];

        return $rules;
    }

    /**
     * مسح الكاش المتعلق بالمنتجات
     */
    private function clearProductsCache(Product $product)
    {
        // مسح كاش صفحات المنتجات
        Cache::tags(['products'])->flush();
        
        // مسح كاش المنتج المحدد بجميع اللغات
        foreach (config('app.locales') as $locale) {
            Cache::forget("product.{$product->id}.{$locale}");
        }

        // مسح كاش المنشأة إذا كان المنتج مرتبط بمنشأة
        if ($product->facility_id) {
            Cache::tags(['facility.' . $product->facility_id])->flush();
        }
    }
}

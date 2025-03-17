<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Feature;
use App\Models\ProductAttributeValue;
use App\Models\ProductFeature;
use App\Models\ProductTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('translations')->latest()->paginate(10);
        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $facilities = Facility::all();
        $attributes = Attribute::all();
        $features = Feature::all();

        return view('dashboard.products.create', compact('categories', 'facilities', 'attributes', 'features'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'property_type' => 'nullable|string',
            'is_active' => 'nullable',
            'image' => 'nullable|image',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480',
            'image_gallery.*' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);


        DB::beginTransaction();

        try {
            // Upload main image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            // Upload video if exists
            $videoPath = null;
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('products/videos', 'public');
            }

            // Upload gallery images if exists
            $galleryImages = [];
            if ($request->hasFile('image_gallery')) {
                foreach ($request->file('image_gallery') as $image) {
                    $galleryImages[] = $image->store('products/gallery', 'public');
                }
            }

            // Create product
            $product = Product::create([
                'price' => $request->price,
                'image' => $imagePath,
                'video' => $videoPath,
                'image_gallery' => !empty($galleryImages) ? json_encode($galleryImages) : null,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'google_maps_url' => $request->google_maps_url,
                'facility_id' => $request->facility_id,
                'property_type' => $request->property_type,
                'owner_user_id' => auth()->id() ?? 1, // استخدام 1 كقيمة افتراضية إذا لم يكن المستخدم مسجل الدخول
                'seller_user_id' => auth()->id() ?? 1, // استخدام 1 كقيمة افتراضية إذا لم يكن المستخدم مسجل الدخول
                'category_id' => $request->category_id,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            // Create translations
            ProductTranslation::create([
                'product_id' => $product->id,
                'name' => $request->name_ar,
                'description' => $request->description_ar,
                'locale' => 'ar',
            ]);

            ProductTranslation::create([
                'product_id' => $product->id,
                'name' => $request->name_en,
                'description' => $request->description_en,
                'locale' => 'en',
            ]);

            // Save attributes
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeId => $value) {
                    if (!empty($value)) {
                        ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'value' => $value,
                        ]);
                    }
                }
            }

            // Save features
            if ($request->has('features')) {
                foreach ($request->features as $featureId) {
                    ProductFeature::create([
                        'product_id' => $product->id,
                        'feature_id' => $featureId,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'تم إضافة المنتج بنجاح');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة المنتج: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load('translations', 'attributeValues.attribute', 'features.feature');
        return view('dashboard.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $facilities = Facility::all();
        $attributes = Attribute::all();
        $features = Feature::all();

        $product->load('translations', 'attributeValues', 'features');

        return view('dashboard.products.edit', compact('product', 'categories', 'facilities', 'attributes', 'features'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'property_type' => 'nullable|string',
            'is_active' => 'nullable',
            'image' => 'nullable|image',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480',
            'image_gallery.*' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        DB::beginTransaction();

        try {
            // Update main image if provided
            $imagePath = $product->image;
            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
            } elseif ($request->has('delete_image') && $request->delete_image) {
                // Delete image if requested
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = null;
            }

            // Update video if provided
            $videoPath = $product->video;
            if ($request->hasFile('video')) {
                // Delete old video
                if ($product->video) {
                    Storage::disk('public')->delete($product->video);
                }
                $videoPath = $request->file('video')->store('products/videos', 'public');
            } elseif ($request->has('delete_video') && $request->delete_video) {
                // Delete video if requested
                if ($product->video) {
                    Storage::disk('public')->delete($product->video);
                }
                $videoPath = null;
            }

            // Update gallery images if provided
            $galleryImages = json_decode($product->image_gallery, true) ?: [];

            // Delete selected gallery images
            if ($request->has('delete_gallery')) {
                foreach ($request->delete_gallery as $index) {
                    if (isset($galleryImages[$index])) {
                        Storage::disk('public')->delete($galleryImages[$index]);
                        unset($galleryImages[$index]);
                    }
                }
                // Re-index array
                $galleryImages = array_values($galleryImages);
            }

            // Add new gallery images
            if ($request->hasFile('image_gallery')) {
                foreach ($request->file('image_gallery') as $image) {
                    $galleryImages[] = $image->store('products/gallery', 'public');
                }
            }

            // Update product
            $product->update([
                'price' => $request->price,
                'image' => $imagePath,
                'video' => $videoPath,
                'image_gallery' => !empty($galleryImages) ? json_encode($galleryImages) : null,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'google_maps_url' => $request->google_maps_url,
                'facility_id' => $request->facility_id,
                'property_type' => $request->property_type,
                'category_id' => $request->category_id,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            // Update translations
            $arTranslation = $product->translations()->where('locale', 'ar')->first();
            if ($arTranslation) {
                $arTranslation->update([
                    'name' => $request->name_ar,
                    'description' => $request->description_ar,
                ]);
            } else {
                ProductTranslation::create([
                    'product_id' => $product->id,
                    'name' => $request->name_ar,
                    'description' => $request->description_ar,
                    'locale' => 'ar',
                ]);
            }

            $enTranslation = $product->translations()->where('locale', 'en')->first();
            if ($enTranslation) {
                $enTranslation->update([
                    'name' => $request->name_en,
                    'description' => $request->description_en,
                ]);
            } else {
                ProductTranslation::create([
                    'product_id' => $product->id,
                    'name' => $request->name_en,
                    'description' => $request->description_en,
                    'locale' => 'en',
                ]);
            }

            // Update attributes
            $product->attributeValues()->delete();
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeId => $value) {
                    if (!empty($value)) {
                        ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'value' => $value,
                        ]);
                    }
                }
            }

            // Update features
            $product->features()->delete();
            if ($request->has('features')) {
                foreach ($request->features as $featureId) {
                    ProductFeature::create([
                        'product_id' => $product->id,
                        'feature_id' => $featureId,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'تم تحديث المنتج بنجاح');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete product image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Delete product video
            if ($product->video) {
                Storage::disk('public')->delete($product->video);
            }

            // Delete gallery images
            if ($product->image_gallery) {
                $galleryImages = json_decode($product->image_gallery, true);
                foreach ($galleryImages as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            // Delete related data
            $product->translations()->delete();
            $product->attributeValues()->delete();
            $product->features()->delete();

            // Delete product
            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'تم حذف المنتج بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المنتج: ' . $e->getMessage());
        }
    }
}

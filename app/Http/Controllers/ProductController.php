<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getReverseGeocode($latitude, $longitude)
    {
        $client = new \GuzzleHttp\Client;
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $language = app()->getLocale();
        $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&language={$language}&key={$apiKey}");
        $data = json_decode($response->getBody());

        return $data->results[0]->formatted_address ?? 'Address not found';
    }

    public function index(Request $request)
    {

        $query = Product::with(['facility', 'translations', 'attributes']);

        if ($request->filled('search_term')) {
            $query->whereHas('translations', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%'.$request->search_term.'%')
                    ->where('locale', app()->getLocale());
            });
        }

        $attributes = collect();
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
            $attributes = Attribute::with('translations')
                ->whereHas('categories', function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                })
                ->get();

            foreach ($attributes as $attribute) {
                $attributeField = 'attribute_'.$attribute->id;
                if ($request->filled($attributeField)) {
                    $query->whereHas('attributes', function ($subQuery) use ($attribute, $request, $attributeField) {
                        $subQuery->where('attribute_id', $attribute->id)
                            ->where('value', $request->$attributeField);
                    });
                }
            }
        }

        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('facility_id')) {
            $query->whereHas('facility', function ($subQuery) use ($request) {
                $subQuery->where('id', $request->facility_id);
            });
        }

        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->sort_direction ?? 'asc');
        }

        $categories = Category::all();
        $products = product::all();
        $facilities = Facility::all();

        return view('parts.product', compact('products', 'categories', 'facilities', 'attributes'));
    }

    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::with('translations')->get();

        return view('products.create', compact('categories',  'attributes'));
    }

    public function store(Request $request)
    {

        $facilityId = auth()->user()->facility_id;

        $product = new Product;
        $product->is_active = $request->has('is_active');
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->latitude = $request->latitude;
        $product->longitude = $request->longitude;
        $product->google_maps_url = $request->google_maps_url;
        $product->seller_user_id = auth()->user()->id;

        $product->facility_id = auth()->user()->facility_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products/images', 'public');
            $product->image = $imagePath;
        }

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('products/videos', 'public');
            $product->video = $videoPath;
        }

        if ($request->hasFile('image_gallery')) {
            $images = [];
            foreach ($request->file('image_gallery') as $image) {
                $imageName = time().rand(1, 1000).'.'.$image->extension();
                $image->storeAs('products/image_gallery', $imageName, 'public');
                $images[] = $imageName;
            }
            $product->image_gallery = implode(',', $images);
        }

        $product->save();

        // حفظ الترجمات
        if ($request->has('translations')) {
            $translations = [];
            foreach ($request->input('translations') as $locale => $translationData) {
                $translations[] = [
                    'product_id' => $product->id,
                    'locale' => $locale,
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? '',
                ];
            }
            ProductTranslation::insert($translations);
        }

        // حفظ السمات
        if ($request->filled('attributes')) {
            foreach ($request->input('attributes') as $attributeId => $value) {
                $attribute = Attribute::findOrFail($attributeId);
                $processedValue = $this->processAttributeValue($attribute, $value);
                $product->attributes()->attach($attributeId, ['value' => $processedValue]);
            }
        }

        return redirect()->route('products.index', ['facility' => $facilityId])->with('success', 'تم إضافة المنتج بنجاح.');
    }

    protected function processAttributeValue($attribute, $value)
    {
        switch ($attribute->type) {
            case 'number':
                return floatval($value);
            case 'checkbox':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'text':
            case 'email':
            case 'url':
                return strip_tags($value);
            default:
                return $value;
        }
    }
}

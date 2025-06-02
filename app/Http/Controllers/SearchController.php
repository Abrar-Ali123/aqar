<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Product;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        
        // الفئات والخصائص
        $categories = Category::with('children')->active()->get();
        $features = Feature::with('options')
            ->active()
            ->get()
            ->groupBy('type');

        // الفلاتر النشطة
        $activeFilters = collect();
        
        if ($request->filled('q')) {
            $activeFilters->put('q', $request->q);
        }
        
        if ($request->filled('category')) {
            $activeFilters->put('category', $request->category);
        }
        
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $activeFilters->put('price', [
                'min' => $request->price_min,
                'max' => $request->price_max
            ]);
        }
        
        if ($request->filled('features')) {
            $activeFilters->put('features', $request->features);
        }
        
        if ($request->filled(['lat', 'lng'])) {
            $activeFilters->put('location', [
                'lat' => $request->lat,
                'lng' => $request->lng,
                'distance' => $request->distance ?? 5
            ]);
        }

        // البحث عن المنتجات
        $products = Product::with(['media', 'user', 'features'])
            ->when($request->filled('q'), function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', "%{$request->q}%")
                      ->orWhere('description', 'like', "%{$request->q}%");
                });
            })
            ->when($request->filled('category'), function($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->when($request->filled('price_min'), function($query) use ($request) {
                $query->where('price', '>=', $request->price_min);
            })
            ->when($request->filled('price_max'), function($query) use ($request) {
                $query->where('price', '<=', $request->price_max);
            })
            ->when($request->filled('features'), function($query) use ($request) {
                foreach($request->features as $featureId => $value) {
                    if (is_array($value)) {
                        if (!empty($value['min'])) {
                            $query->whereHas('features', function($q) use ($featureId, $value) {
                                $q->where('feature_id', $featureId)
                                  ->where('value', '>=', $value['min']);
                            });
                        }
                        if (!empty($value['max'])) {
                            $query->whereHas('features', function($q) use ($featureId, $value) {
                                $q->where('feature_id', $featureId)
                                  ->where('value', '<=', $value['max']);
                            });
                        }
                    } else {
                        $query->whereHas('features', function($q) use ($featureId, $value) {
                            $q->where('feature_id', $featureId)
                              ->where('value', $value);
                        });
                    }
                }
            })
            ->when($request->filled(['lat', 'lng']), function($query) use ($request) {
                $distance = $request->distance ?? 5;
                $lat = $request->lat;
                $lng = $request->lng;
                
                $query->selectRaw("
                    *, 
                    (6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) AS distance", 
                    [$lat, $lng, $lat]
                )
                ->having('distance', '<=', $distance)
                ->orderBy('distance');
            })
            ->when($request->filled('sort'), function($query) use ($request) {
                switch($request->sort) {
                    case 'date_desc':
                        $query->latest();
                        break;
                    case 'date_asc':
                        $query->oldest();
                        break;
                    case 'price_asc':
                        $query->orderBy('price');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'views':
                        $query->orderBy('views', 'desc');
                        break;
                }
            }, function($query) {
                $query->latest();
            })
            ->paginate(12)
            ->withQueryString();

        return view('themes.real_estate.search.index', compact(
            'categories',
            'features',
            'activeFilters',
            'products'
        ));
    }

    public function results(Request $request)
    {
        $locale = $request->route('locale');
        $query = $request->get('q');
        $type = $request->get('type', 'products');  
        $category = $request->get('category');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $location = $request->get('location');
        $sortBy = $request->get('sort_by', 'relevance');

        if ($query) {
            if ($type === 'facilities') {
                $products = $this->searchFacilities($locale, $query, $category, $minPrice, $maxPrice, $location, $sortBy);
            } else {
                $products = $this->searchProducts($locale, $query, $category, $minPrice, $maxPrice, $sortBy);
            }
        } else {
            $products = collect();
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('search.results', compact('locale', 'products', 'query', 'type', 'category', 'minPrice', 'maxPrice', 'location', 'sortBy'))->render()
            ]);
        }

        return view('search.results', compact('locale', 'products', 'query', 'type', 'category', 'minPrice', 'maxPrice', 'location', 'sortBy'));
    }

    public function suggestions(Request $request)
    {
        $locale = $request->route('locale');
        $query = $request->get('q');
        $type = $request->get('type', 'all');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = [];

        if ($type === 'all' || $type === 'facilities') {
            $facilities = Facility::active()
                ->whereTranslationLike('name', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'price', 'slug']);

            foreach ($facilities as $facility) {
                $suggestions[] = [
                    'id' => $facility->id,
                    'text' => $facility->name,
                    'type' => 'facility',
                    'price' => $facility->price,
                    'url' => route('facilities.show', ['locale' => $locale, 'facility' => $facility->slug])
                ];
            }
        }

        if ($type === 'all' || $type === 'products') {
            $products = Product::active()
                ->whereTranslationLike('name', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'price', 'slug']);

            foreach ($products as $product) {
                $suggestions[] = [
                    'id' => $product->id,
                    'text' => $product->name,
                    'type' => 'product',
                    'price' => $product->price,
                    'url' => route('products.show', ['locale' => $locale, 'product' => $product->slug])
                ];
            }
        }

        return response()->json($suggestions);
    }

    protected function searchFacilities($locale, $query, $category = null, $minPrice = null, $maxPrice = null, $location = null, $sortBy = 'relevance')
    {
        $facilities = Facility::with(['category', 'features', 'media'])
            ->active()
            ->whereTranslationLike('name', "%{$query}%")
            ->orWhereTranslationLike('description', "%{$query}%");

        if ($category) {
            $facilities->where('category_id', $category);
        }

        if ($minPrice) {
            $facilities->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $facilities->where('price', '<=', $maxPrice);
        }

        if ($location) {
            $facilities->where(function ($q) use ($location) {
                $q->where('address', 'like', "%{$location}%")
                    ->orWhere('city', 'like', "%{$location}%")
                    ->orWhere('state', 'like', "%{$location}%")
                    ->orWhere('country', 'like', "%{$location}%");
            });
        }

        switch ($sortBy) {
            case 'price_asc':
                $facilities->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $facilities->orderBy('price', 'desc');
                break;
            case 'date_desc':
                $facilities->latest();
                break;
            case 'rating':
                $facilities->orderBy('average_rating', 'desc');
                break;
            default:
                $facilities->orderByRaw('MATCH(name) AGAINST(? IN BOOLEAN MODE) DESC', [$query]);
        }

        return $facilities->paginate(12);
    }

    protected function searchProducts($locale, $query, $category = null, $minPrice = null, $maxPrice = null, $sortBy = 'relevance')
    {
        $products = Product::with(['category', 'media'])
            ->active()
            ->whereTranslationLike('name', "%{$query}%")
            ->orWhereTranslationLike('description', "%{$query}%");

        if ($category) {
            $products->where('category_id', $category);
        }

        if ($minPrice) {
            $products->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $products->where('price', '<=', $maxPrice);
        }

        switch ($sortBy) {
            case 'price_asc':
                $products->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('price', 'desc');
                break;
            case 'date_desc':
                $products->latest();
                break;
            case 'rating':
                $products->orderBy('average_rating', 'desc');
                break;
            default:
                $products->orderByRaw('MATCH(name) AGAINST(? IN BOOLEAN MODE) DESC', [$query]);
        }

        return $products->paginate(12);
    }
}

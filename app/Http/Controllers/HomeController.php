<?php

namespace App\Http\Controllers;

use App\Models\BusinessSector;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // الصفحة الرئيسية متاحة للجميع
        // $this->middleware('auth');
    }

    /**
     * عرض الصفحة الرئيسية
     */
    public function index(): View
    {
        $locale = app()->getLocale();

        // المنشآت المميزة
        $featuredFacilities = Facility::where('is_active', true)
            ->where('is_featured', true)
            ->with([
                'translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'businessCategory.translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'businessSector.translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                }
            ])
            ->withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('featuredFacilities'));
    }

    /**
     * Search products, services, and facilities
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');
        $locale = app()->getLocale();

        $products = Product::query()
            ->select('products.*')
            ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('product_translations.locale', $locale)
            ->where('products.is_active', true)
            ->when($query, function ($q) use ($query) {
                $q->where(function($q) use ($query) {
                    $q->where('product_translations.name', 'like', "%{$query}%")
                      ->orWhere('product_translations.description', 'like', "%{$query}%");
                });
            })
            ->when($type, function ($q) use ($type) {
                $q->where('products.type', $type);
            })
            ->with(['facilities' => function($q) use ($locale) {
                $q->select('facilities.*')
                    ->join('facility_translations', 'facilities.id', '=', 'facility_translations.facility_id')
                    ->where('facility_translations.locale', $locale);
            }, 'category' => function($q) use ($locale) {
                $q->select('categories.*')
                    ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', $locale);
            }])
            ->latest()
            ->paginate(12);

        return view('products.index', [
            'products' => $products,
            'search_query' => $query,
            'type' => $type
        ]);
    }

    /**
     * عرض صفحة السوق الإلكتروني
     */
    public function marketplace(): View
    {
        // المنتجات المميزة
        $featuredProducts = Product::with(['facilities', 'media'])
            ->whereHas('facilities', function($query) {
                $query->where('facilities.is_active', true);
            })
            ->where('is_featured', true)
            ->active()
            ->latest()
            ->take(8)
            ->get();

        // التصنيفات الرئيسية
        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        // المنشآت المميزة
        $featuredFacilities = Facility::active()
            ->withCount('products')
            ->withAvg(['reviews' => function($query) {
                $query->where('is_approved', true);
            }], 'rating')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        return view('marketplace', compact(
            'featuredProducts',
            'categories',
            'featuredFacilities'
        ));
    }

    /**
     * عرض صفحة المجتمع
     */
    public function community(): View
    {
        return view('community.index');
    }
}

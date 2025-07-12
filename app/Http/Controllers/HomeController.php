<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Language;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية مع دعم تعدد اللغات
     */
    public function index(): View
    {
        // جلب البيانات بشكل بسيط
        $languages = Language::all();
        $categories = Category::all();
        $products = Product::all();
        $facilities = Facility::all();

        return view('home', compact(
            'languages',
            'categories',
            'products',
            'facilities'
        ));
    }
}

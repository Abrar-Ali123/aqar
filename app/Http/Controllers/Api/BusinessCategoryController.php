<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessSector;
use App\Models\BusinessCategory;
use App\Models\BusinessSubcategory;
use App\Services\BusinessCategoryService;
use Illuminate\Http\Request;

class BusinessCategoryController extends Controller
{
    protected $service;

    public function __construct(BusinessCategoryService $service)
    {
        $this->service = $service;
    }

    // جلب القطاعات الرئيسية
    public function sectors()
    {
        $sectors = BusinessSector::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        return response()->json(['data' => $sectors]);
    }

    // جلب الفئات حسب القطاع
    public function categories($sectorId)
    {
        $categories = BusinessCategory::where('sector_id', $sectorId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        return response()->json(['data' => $categories]);
    }

    // جلب الفئات الفرعية حسب الفئة
    public function subcategories($categoryId)
    {
        $subcategories = BusinessSubcategory::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        return response()->json(['data' => $subcategories]);
    }

    // جلب الوحدات المقترحة حسب التصنيف
    public function modules(Request $request)
    {
        $sectorId = $request->input('sector_id');
        $categoryId = $request->input('category_id');
        $subcategoryId = $request->input('subcategory_id');

        $sector = BusinessSector::findOrFail($sectorId);
        $category = $categoryId ? BusinessCategory::findOrFail($categoryId) : null;
        $subcategory = $subcategoryId ? BusinessSubcategory::findOrFail($subcategoryId) : null;

        $modules = $this->service->getAvailableModules($sector, $category, $subcategory);
        return response()->json(['data' => $modules]);
    }
}

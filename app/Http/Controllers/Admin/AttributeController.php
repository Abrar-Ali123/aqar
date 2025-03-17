<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeTranslation;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    /**
     * Display a listing of the attributes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::with('translations', 'category.translations')->paginate(10);
        return view('dashboard.attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new attribute.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::with('translations')->get();
        return view('dashboard.attributes.create', compact('categories'));
    }

    /**
     * Store a newly created attribute in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'symbol_ar' => 'nullable|string|max:50',
            'symbol_en' => 'nullable|string|max:50',
            'type' => 'required|string|in:text,number,select,checkbox,radio,date,color',
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:255',
            'Symbol' => 'nullable|string|max:50',
            'required' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Create attribute
            $attribute = Attribute::create([
                'type' => $request->type,
                'required' => $request->has('required'),
                'category_id' => $request->category_id,
                'icon' => $request->icon,
                'Symbol' => $request->Symbol,
            ]);

            // Create translations
            AttributeTranslation::create([
                'attribute_id' => $attribute->id,
                'locale' => 'ar',
                'name' => $request->name_ar,
                'symbol' => $request->symbol_ar,
            ]);

            AttributeTranslation::create([
                'attribute_id' => $attribute->id,
                'locale' => 'en',
                'name' => $request->name_en,
                'symbol' => $request->symbol_en,
            ]);

            DB::commit();

            return redirect()->route('admin.attributes.index')
                ->with('success', 'تم إضافة الخاصية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة الخاصية: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified attribute.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        $attribute->load('translations', 'category.translations');
        return view('dashboard.attributes.show', compact('attribute'));
    }

    /**
     * Show the form for editing the specified attribute.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        $categories = Category::with('translations')->get();
        return view('dashboard.attributes.edit', compact('attribute', 'categories'));
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'symbol_ar' => 'nullable|string|max:50',
            'symbol_en' => 'nullable|string|max:50',
            'type' => 'required|string|in:text,number,select,checkbox,radio,date,color',
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:255',
            'Symbol' => 'nullable|string|max:50',
            'required' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Update attribute
            $attribute->update([
                'type' => $request->type,
                'required' => $request->has('required'),
                'category_id' => $request->category_id,
                'icon' => $request->icon,
                'Symbol' => $request->Symbol,
            ]);

            // Update translations
            $attribute->translations()->where('locale', 'ar')->update([
                'name' => $request->name_ar,
                'symbol' => $request->symbol_ar,
            ]);

            $attribute->translations()->where('locale', 'en')->update([
                'name' => $request->name_en,
                'symbol' => $request->symbol_en,
            ]);

            DB::commit();

            return redirect()->route('admin.attributes.index')
                ->with('success', 'تم تحديث الخاصية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الخاصية: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified attribute from storage.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        // Check if attribute has values
        if ($attribute->attributeValues()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الخاصية لأنها مستخدمة في منتجات');
        }

        DB::beginTransaction();

        try {
            // Delete translations
            $attribute->translations()->delete();

            // Delete attribute
            $attribute->delete();

            DB::commit();

            return redirect()->route('admin.attributes.index')
                ->with('success', 'تم حذف الخاصية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الخاصية: ' . $e->getMessage());
        }
    }

    /**
     * Get attributes by category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAttributesByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $attributes = Attribute::with('translations')
            ->where('category_id', $categoryId)
            ->orWhereNull('category_id')
            ->get();

        return response()->json($attributes);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LanguageController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view languages')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::orderBy('order')->paginate(10);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        if (!auth()->user()->can('create languages')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create languages')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء اللغة
                $language = Language::create([
                    'name' => $validated['name'],
                    'code' => $validated['code'],
                    'direction' => $validated['direction'],
                    'is_default' => $request->boolean('is_default'),
                    'is_required' => $request->boolean('is_required'),
                    'status' => $request->status ?? 'active',
                    'order' => $request->order ?? 0,
                ]);

                // معالجة الصورة
                if ($request->hasFile('flag')) {
                    $path = $request->file('flag')->store('languages/flags', 'public');
                    $language->update(['flag' => $path]);
                }

                // إنشاء ملف الترجمة
                if ($request->boolean('create_translation_file')) {
                    $this->createTranslationFile($language->code);
                }

                // إذا كانت اللغة الافتراضية، نجعل باقي اللغات غير افتراضية
                if ($language->is_default) {
                    Language::where('id', '!=', $language->id)
                        ->update(['is_default' => false]);
                }
            });

            return redirect()->route('admin.languages.index')
                ->with('success', __('messages.language_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.language_create_error'))
                ->withInput();
        }
    }

    public function edit(Language $language)
    {
        if (!auth()->user()->can('edit languages')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        if (!auth()->user()->can('edit languages')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($language->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $language, $validated) {
                // تحديث اللغة
                $language->update([
                    'name' => $validated['name'],
                    'code' => $validated['code'],
                    'direction' => $validated['direction'],
                    'is_default' => $request->boolean('is_default'),
                    'is_required' => $request->boolean('is_required'),
                    'status' => $request->status ?? $language->status,
                    'order' => $request->order ?? $language->order,
                ]);

                // معالجة الصورة
                if ($request->hasFile('flag')) {
                    if ($language->flag) {
                        Storage::disk('public')->delete($language->flag);
                    }
                    $path = $request->file('flag')->store('languages/flags', 'public');
                    $language->update(['flag' => $path]);
                }

                // إنشاء ملف الترجمة إذا لم يكن موجوداً
                if ($request->boolean('create_translation_file')) {
                    $this->createTranslationFile($language->code);
                }

                // إذا كانت اللغة الافتراضية، نجعل باقي اللغات غير افتراضية
                if ($language->is_default) {
                    Language::where('id', '!=', $language->id)
                        ->update(['is_default' => false]);
                }
                // إذا كانت اللغة الوحيدة، نجعلها افتراضية وإجبارية
                elseif (Language::count() === 1) {
                    $language->update([
                        'is_default' => true,
                        'is_required' => true,
                    ]);
                }
            });

            return redirect()->route('admin.languages.index')
                ->with('success', __('messages.language_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.language_update_error'))
                ->withInput();
        }
    }

    public function destroy(Language $language)
    {
        if (!auth()->user()->can('delete languages')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // لا يمكن حذف اللغة الإجبارية
        if ($language->is_required) {
            return redirect()->back()
                ->with('error', __('messages.cannot_delete_required_language'));
        }

        // التحقق من عدم وجود ترجمات
        if ($this->hasTranslations($language)) {
            return redirect()->back()
                ->with('error', __('messages.language_has_translations'));
        }

        try {
            DB::transaction(function () use ($language) {
                // حذف الصورة
                if ($language->flag) {
                    Storage::disk('public')->delete($language->flag);
                }

                // حذف ملف الترجمة
                $this->deleteTranslationFile($language->code);

                // حذف اللغة
                $language->delete();

                // إذا كانت اللغة الافتراضية، نجعل أول لغة متبقية هي الافتراضية
                if ($language->is_default) {
                    $firstLanguage = Language::first();
                    if ($firstLanguage) {
                        $firstLanguage->update(['is_default' => true]);
                    }
                }
            });

            return redirect()->route('admin.languages.index')
                ->with('success', __('messages.language_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.language_delete_error'));
        }
    }

    private function getValidationRules($languageId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'size:2',
                'unique:languages,code' . ($languageId ? ",{$languageId}" : ''),
            ],
            'direction' => 'required|in:ltr,rtl',
            'flag' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_default' => 'boolean',
            'is_required' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'create_translation_file' => 'boolean',
        ];
    }

    private function createTranslationFile(string $code): void
    {
        $path = resource_path("lang/{$code}.json");
        
        // إنشاء المجلد إذا لم يكن موجوداً
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        // إنشاء الملف إذا لم يكن موجوداً
        if (!File::exists($path)) {
            File::put($path, '{}');
        }
    }

    private function deleteTranslationFile(string $code): void
    {
        $path = resource_path("lang/{$code}.json");
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function hasTranslations(Language $language): bool
    {
        // التحقق من وجود ترجمات في أي من الجداول التي تحتوي على ترجمات
        return DB::table('attribute_translations')
            ->where('locale', $language->code)
            ->exists()
        || DB::table('category_translations')
            ->where('locale', $language->code)
            ->exists()
        || DB::table('feature_translations')
            ->where('locale', $language->code)
            ->exists()
        || DB::table('product_translations')
            ->where('locale', $language->code)
            ->exists();
    }
}

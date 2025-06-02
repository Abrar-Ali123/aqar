<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Translation, Language};
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $query = Translation::query();
        
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('key', 'like', "%{$request->search}%")
                  ->orWhere('text', 'like', "%{$request->search}%");
            });
        }

        $translations = $query->paginate(20);
        $groups = Translation::distinct('group')->pluck('group');
        $languages = Language::where('is_active', true)->get();

        return view('admin.translations.index', compact('translations', 'groups', 'languages'));
    }

    public function edit($id)
    {
        $translation = Translation::findOrFail($id);
        $languages = Language::where('is_active', true)->get();
        
        return view('admin.translations.edit', compact('translation', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $translation = Translation::findOrFail($id);
        
        $request->validate([
            'text' => 'required|string',
        ]);

        $translation->update([
            'text' => $request->text,
        ]);

        // حذف الكاش للترجمة المحدثة
        Cache::forget("translation:{$translation->locale}:{$translation->group}:{$translation->key}");

        return redirect()->route('admin.translations.index')
            ->with('success', __('messages.success'));
    }

    public function import()
    {
        Translation::importFromFiles();
        return redirect()->route('admin.translations.index')
            ->with('success', 'تم استيراد الترجمات بنجاح');
    }

    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        $groups = Translation::distinct('group')->pluck('group');
        
        return view('admin.translations.create', compact('languages', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'group' => 'required|string',
            'translations' => 'required|array',
        ]);

        Translation::updateOrCreateTranslation(
            $request->key,
            $request->group,
            $request->translations
        );

        return redirect()->route('admin.translations.index')
            ->with('success', __('messages.success'));
    }
}

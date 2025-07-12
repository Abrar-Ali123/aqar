<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLanguageRequest;
use App\Http\Requests\Admin\UpdateLanguageRequest;
use App\Models\Language;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }
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

    public function store(StoreLanguageRequest $request)
    {
        try {
            $this->languageService->createLanguage($request->validated());
            return redirect()->route('admin.languages.index')
                ->with('success', __('messages.language_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
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

    public function update(UpdateLanguageRequest $request, Language $language)
    {
        try {
            $this->languageService->updateLanguage($language, $request->validated());
            return redirect()->route('admin.languages.index')
                ->with('success', __('messages.language_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Language $language)
    {
        if (!auth()->user()->can('delete languages')) {
            abort(403, __('messages.unauthorized_action'));
        }

        try {
            $this->languageService->deleteLanguage($language);
            return redirect()->route('admin.languages.index')
                ->with('success', __('messages.language_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }


}

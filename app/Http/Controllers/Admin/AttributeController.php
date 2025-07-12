<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Language;
use App\Http\Requests\Admin\StoreAttributeRequest;
use App\Http\Requests\Admin\UpdateAttributeRequest;
use App\Services\AttributeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }
    public function index()
    {
        if (!auth()->user()->can('view attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $attributes = Attribute::with(['translations', 'category'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        if (!auth()->user()->can('create attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        return view('admin.attributes.create', compact('languages'));
    }

    public function store(StoreAttributeRequest $request)
    {
        try {
            $this->attributeService->createAttribute($request->validated());
            return redirect()->route('admin.attributes.index')
                ->with('success', __('messages.attribute_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.attribute_create_error'))
                ->withInput();
        }
    }

    public function edit(Attribute $attribute)
    {
        if (!auth()->user()->can('edit attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $attribute->translations->keyBy('locale');
        $optionTranslations = $attribute->options->load('translations')
            ->map(function ($option) {
                return [
                    'id' => $option->id,
                    'translations' => $option->translations->keyBy('locale'),
                ];
            })
            ->keyBy('id');

        return view('admin.attributes.edit', compact('attribute', 'languages', 'translations', 'optionTranslations'));
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        try {
            $this->attributeService->updateAttribute($attribute, $request->validated());
            return redirect()->route('admin.attributes.index')
                ->with('success', __('messages.attribute_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Attribute $attribute)
    {
        if (!auth()->user()->can('delete attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        try {
            $this->attributeService->deleteAttribute($attribute);
            return redirect()->route('admin.attributes.index')
                ->with('success', __('messages.attribute_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }


}

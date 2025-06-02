<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IconController extends Controller
{
    public function index()
    {
        $icons = Icon::with('translations')->latest()->paginate(10);
        return view('icons.index', compact('icons'));
    }

    public function create()
    {
        return view('icons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'file' => 'required|image|mimes:svg,png|max:2048',
            'type' => 'required|in:general,category,feature,facility',
            'is_active' => 'boolean',
        ]);

        try {
            $filePath = $request->file('file')->store('icons', 'public');

            $icon = Icon::create([
                'file' => $filePath,
                'type' => $request->type,
                'is_active' => $request->boolean('is_active'),
            ]);

            foreach (array_keys(config('app.locales')) as $locale) {
                $icon->translations()->updateOrCreate(['locale' => $locale], [
                    'name' => $request->input("translations.{$locale}.name"),
                    'description' => $request->input("translations.{$locale}.description", ''),
                ]);
            }

            return redirect()->route('icons.index')
                ->with('success', __('messages.icon_created_successfully'));
        } catch (\Exception $e) {
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return redirect()->back()
                ->with('error', __('messages.icon_create_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Icon $icon)
    {
        $icon->load('translations');
        return view('icons.edit', compact('icon'));
    }

    public function update(Request $request, Icon $icon)
    {
        $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'file' => 'nullable|image|mimes:svg,png|max:2048',
            'type' => 'required|in:general,category,feature,facility',
            'is_active' => 'boolean',
        ]);

        try {
            if ($request->hasFile('file')) {
                if ($icon->file) {
                    Storage::disk('public')->delete($icon->file);
                }
                $icon->file = $request->file('file')->store('icons', 'public');
            }

            $icon->update([
                'type' => $request->type,
                'is_active' => $request->boolean('is_active'),
            ]);

            foreach (array_keys(config('app.locales')) as $locale) {
                $icon->translations()->updateOrCreate(['locale' => $locale], [
                    'name' => $request->input("translations.{$locale}.name"),
                    'description' => $request->input("translations.{$locale}.description", ''),
                ]);
            }

            return redirect()->route('icons.index')
                ->with('success', __('messages.icon_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.icon_update_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Icon $icon)
    {
        try {
            if ($icon->file) {
                Storage::disk('public')->delete($icon->file);
            }

            $icon->translations()->delete();
            $icon->delete();

            return redirect()->route('icons.index')
                ->with('success', __('messages.icon_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.icon_delete_error') . ': ' . $e->getMessage());
        }
    }
}

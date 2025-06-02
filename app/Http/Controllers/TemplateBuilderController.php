<?php

namespace App\Http\Controllers;

use App\Models\PageTemplate;
use Illuminate\Http\Request;

class TemplateBuilderController extends Controller
{
    public function create()
    {
        return view('template-builder.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'thumbnail' => 'required|image|max:2048',
            'layout' => 'required|json',
            'styles' => 'required|json',
            'components' => 'required|array',
            'features' => 'required|array',
            'is_public' => 'boolean'
        ]);

        $template = new PageTemplate();
        $template->name = $validated['name'];
        $template->description = $validated['description'];
        $template->category = $validated['category'];
        $template->layout = $validated['layout'];
        $template->styles = $validated['styles'];
        $template->components = json_encode($validated['components']);
        $template->features = json_encode($validated['features']);
        $template->is_public = $validated['is_public'] ?? false;
        
        if ($request->hasFile('thumbnail')) {
            $template->thumbnail = $request->file('thumbnail')->store('templates/thumbnails', 'public');
        }

        $template->save();

        return redirect()->route('website-templates.index')
            ->with('success', 'تم إنشاء القالب بنجاح');
    }

    public function edit(PageTemplate $template)
    {
        return view('template-builder.edit', compact('template'));
    }

    public function update(Request $request, PageTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'layout' => 'required|json',
            'styles' => 'required|json',
            'components' => 'required|array',
            'features' => 'required|array',
            'is_public' => 'boolean'
        ]);

        $template->name = $validated['name'];
        $template->description = $validated['description'];
        $template->category = $validated['category'];
        $template->layout = $validated['layout'];
        $template->styles = $validated['styles'];
        $template->components = json_encode($validated['components']);
        $template->features = json_encode($validated['features']);
        $template->is_public = $validated['is_public'] ?? false;
        
        if ($request->hasFile('thumbnail')) {
            $template->thumbnail = $request->file('thumbnail')->store('templates/thumbnails', 'public');
        }

        $template->save();

        return redirect()->route('website-templates.index')
            ->with('success', 'تم تحديث القالب بنجاح');
    }
}

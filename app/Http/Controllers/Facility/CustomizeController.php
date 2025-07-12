<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\TemplateComponent;
use App\Models\FacilityCustomization;
use Illuminate\Http\Request;

class CustomizeController extends Controller
{
    public function editor(Request $request, Facility $facility)
    {
        $customization = $facility->customization ?? $facility->createDefaultCustomization();
        $components = TemplateComponent::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('facilities.customize.editor', [
            'facility' => $facility,
            'customization' => $customization,
            'components' => $components,
            'availableComponents' => $facility->template->getAvailableComponents()
        ]);
    }

    public function preview(Request $request, Facility $facility)
    {
        $customization = new FacilityCustomization($request->all());
        
        return view('facilities.customize.preview', [
            'facility' => $facility,
            'customization' => $customization
        ]);
    }

    public function save(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'layout' => 'required|array',
            'styles' => 'required|array',
            'components_data' => 'required|array',
            'settings' => 'required|array'
        ]);

        $customization = $facility->customization ?? new FacilityCustomization([
            'facility_id' => $facility->id,
            'template_id' => $facility->template_id
        ]);

        $customization->fill($validated);

        if ($request->input('publish')) {
            $customization->publish();
        } else {
            $customization->saveDraft();
        }

        return response()->json([
            'message' => $request->input('publish') ? 'Changes published successfully' : 'Draft saved successfully',
            'customization' => $customization
        ]);
    }

    public function components(Request $request, Facility $facility)
    {
        $components = TemplateComponent::where('is_active', true)
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->orderBy('sort_order')
            ->get()
            ->map(function ($component) {
                return [
                    'id' => $component->id,
                    'name' => $component->name,
                    'slug' => $component->slug,
                    'type' => $component->type,
                    'icon' => $component->icon,
                    'description' => $component->description,
                    'preview_image' => $component->preview_image,
                    'settings_fields' => $component->getSettingsFields(),
                    'styles_fields' => $component->getStylesFields(),
                    'content_fields' => $component->getContentFields(),
                    'default_settings' => $component->default_settings,
                    'default_styles' => $component->default_styles,
                    'default_content' => $component->default_content
                ];
            });

        return response()->json($components);
    }

    public function revertToRevision(Request $request, Facility $facility)
    {
        $request->validate([
            'revision_id' => 'required|exists:customization_revisions,id'
        ]);

        $customization = $facility->customization;
        $revision = $customization->revertToRevision($request->revision_id);

        return response()->json([
            'message' => 'Reverted to version ' . $revision->version,
            'customization' => $customization
        ]);
    }
}

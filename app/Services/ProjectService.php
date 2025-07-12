<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    public function createProject(Request $request): Project
    {
        return DB::transaction(function () use ($request) {
            $project = new Project();
            $this->fillProjectData($project, $request);
            $project->save();

            $this->syncTranslations($project, $request->input('name', []), $request->all());
            $this->syncImages($project, $request->file('images', []));
            $this->syncFacilities($project, $request->input('facilities', []));
            $this->syncFeatures($project, $request->input('features', []), $request->input('feature_prices', []));

            return $project;
        });
    }

    public function updateProject(Project $project, Request $request): Project
    {
        return DB::transaction(function () use ($project, $request) {
            $this->fillProjectData($project, $request);
            $project->save();

            $this->syncTranslations($project, $request->input('name', []), $request->all());
            $this->syncImages($project, $request->file('images', []), $request->input('delete_images', []), $request->input('image_order', []));
            $this->syncFacilities($project, $request->input('facilities', []));
            $this->syncFeatures($project, $request->input('features', []), $request->input('feature_prices', []));

            return $project;
        });
    }

    public function deleteProject(Project $project): void
    {
        DB::transaction(function () use ($project) {
            foreach ($project->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
            $project->delete();
        });
    }

    private function fillProjectData(Project $project, Request $request): void
    {
        $project->fill($request->only([
            'category_id', 'location', 'latitude', 'longitude', 'start_date', 'end_date',
            'price', 'sale_price', 'units_count', 'status', 'order'
        ]));

        $project->is_active = $request->boolean('is_active');
        $project->is_featured = $request->boolean('is_featured');
        $project->show_in_home = $request->boolean('show_in_home');
    }

    private function syncTranslations(Project $project, array $names, array $data): void
    {
        foreach ($names as $locale => $name) {
            if ($name) {
                 $project->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name' => $name,
                        'description' => $data['description'][$locale] ?? null,
                        'short_description' => $data['short_description'][$locale] ?? null,
                        'address' => $data['address'][$locale] ?? null,
                        'meta_title' => $data['meta_title'][$locale] ?? null,
                        'meta_description' => $data['meta_description'][$locale] ?? null,
                        'slug' => $data['slug'][$locale] ?? null,
                    ]
                );
            }
        }
    }

    private function syncImages(Project $project, array $newImages, array $deletedImages = [], array $imageOrder = []): void
    {
        // Delete images
        if (!empty($deletedImages)) {
            $imagesToDelete = $project->images()->whereIn('id', $deletedImages)->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        // Add new images
        if (!empty($newImages)) {
            foreach ($newImages as $image) {
                $path = $image->store('projects/' . $project->id, 'public');
                $project->images()->create(['path' => $path]);
            }
        }

        // Update image order
        if (!empty($imageOrder)) {
            foreach ($imageOrder as $imageId => $order) {
                $project->images()->where('id', $imageId)->update(['order' => $order]);
            }
        }
    }

    private function syncFacilities(Project $project, array $facilities): void
    {
        $project->facilities()->detach();
        if (!empty($facilities)) {
            foreach ($facilities as $facilityId => $value) {
                $project->facilities()->attach($facilityId, ['value' => $value]);
            }
        }
    }

    private function syncFeatures(Project $project, array $features, array $featurePrices): void
    {
        $project->features()->detach();
        if (!empty($features)) {
            foreach ($features as $featureId => $options) {
                $project->features()->attach($featureId, [
                    'options' => json_encode($options),
                    'price' => $featurePrices[$featureId] ?? 0,
                ]);
            }
        }
    }
}

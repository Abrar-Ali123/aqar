<?php

namespace App\Services;

use App\Models\Feature;
use App\Models\Language;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeatureService
{
    public function getPaginatedFeatures(): LengthAwarePaginator
    {
        return Feature::with(['translations', 'category'])
            ->orderBy('order')
            ->paginate(10);
    }

    public function getFeatureDetails(Feature $feature): Feature
    {
        return $feature->load(['translations', 'category', 'options.translations']);
    }

    public function getActiveLanguages(): Collection
    {
        return Language::active()->orderBy('order')->get();
    }

    public function createFeature(array $data): Feature
    {
        return DB::transaction(function () use ($data) {
            $feature = new Feature();
            $this->fillFeatureData($feature, $data);

            if (isset($data['custom_icon']) && $data['custom_icon'] instanceof UploadedFile) {
                $feature->custom_icon = $data['custom_icon']->store('features/icons', 'public');
            }

            $feature->save();
            $this->syncRelations($feature, $data);

            return $feature;
        });
    }

    public function updateFeature(Feature $feature, array $data): Feature
    {
        return DB::transaction(function () use ($feature, $data) {
            $this->fillFeatureData($feature, $data);

            if (isset($data['custom_icon']) && $data['custom_icon'] instanceof UploadedFile) {
                if ($feature->custom_icon) {
                    Storage::disk('public')->delete($feature->custom_icon);
                }
                $feature->custom_icon = $data['custom_icon']->store('features/icons', 'public');
            }

            $feature->save();
            $this->syncRelations($feature, $data);

            return $feature;
        });
    }

    public function deleteFeature(Feature $feature): void
    {
        if ($feature->products()->exists() || $feature->projects()->exists()) {
            throw new \Exception(__('messages.feature_in_use'));
        }

        DB::transaction(function () use ($feature) {
            if ($feature->custom_icon) {
                Storage::disk('public')->delete($feature->custom_icon);
            }
            $feature->delete();
        });
    }

    private function fillFeatureData(Feature $feature, array $data): void
    {
        $feature->category_id = $data['category_id'] ?? null;
        $feature->icon = $data['icon'] ?? null;
        $feature->icon_type = $data['icon_type'] ?? 'icon';
        $feature->is_required = $data['is_required'] ?? false;
        $feature->is_filterable = $data['is_filterable'] ?? false;
        $feature->show_in_list = $data['show_in_list'] ?? false;
        $feature->show_in_details = $data['show_in_details'] ?? false;
        $feature->status = $data['status'] ?? 'active';
        $feature->order = $data['order'] ?? 0;
    }

    private function syncRelations(Feature $feature, array $data): void
    {
        if (isset($data['name'])) {
            foreach ($data['name'] as $locale => $name) {
                if ($name || Language::where('code', $locale)->value('is_required')) {
                    $feature->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $name,
                            'description' => $data['description'][$locale] ?? null,
                            'help_text' => $data['help_text'][$locale] ?? null,
                        ]
                    );
                }
            }
        }

        $existingOptionIds = $feature->options()->pluck('id')->all();
        $newOptionIds = [];

        if (isset($data['options'])) {
            foreach ($data['options'] as $optionData) {
                $optionId = $optionData['id'] ?? null;
                $featureOption = $feature->options()->updateOrCreate(
                    ['id' => $optionId],
                    [
                        'value' => $optionData['value'],
                        'price' => $optionData['price'] ?? 0,
                        'is_default' => $optionData['is_default'] ?? false,
                        'order' => $optionData['order'] ?? 0,
                    ]
                );
                $newOptionIds[] = $featureOption->id;

                if (isset($optionData['label'])) {
                    foreach ($optionData['label'] as $locale => $label) {
                        if ($label || Language::where('code', $locale)->value('is_required')) {
                            $featureOption->translations()->updateOrCreate(
                                ['locale' => $locale],
                                ['label' => $label]
                            );
                        }
                    }
                }
            }
        }

        $optionsToDelete = array_diff($existingOptionIds, $newOptionIds);
        if (!empty($optionsToDelete)) {
            $feature->options()->whereIn('id', $optionsToDelete)->delete();
        }
    }
}

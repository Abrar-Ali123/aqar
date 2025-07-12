<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

class AttributeService
{
    /**
     * Create a new attribute with its translations and options.
     *
     * @param array $validatedData
     * @return Attribute
     * @throws \Exception
     */
    /**
     * Update an existing attribute with its translations and options.
     *
     * @param Attribute $attribute
     * @param array $validatedData
     * @return Attribute
     * @throws \Exception
     */
    public function updateAttribute(Attribute $attribute, array $validatedData): Attribute
    {
        return DB::transaction(function () use ($attribute, $validatedData) {
            $attribute->update([
                'category_id' => $validatedData['category_id'],
                'type' => $validatedData['type'],
                'validation_rules' => $validatedData['validation_rules'] ?? null,
                'is_required' => $validatedData['is_required'] ?? false,
                'is_filterable' => $validatedData['is_filterable'] ?? false,
                'is_searchable' => $validatedData['is_searchable'] ?? false,
                'show_in_list' => $validatedData['show_in_list'] ?? false,
                'show_in_details' => $validatedData['show_in_details'] ?? false,
                'status' => $validatedData['status'] ?? 'active',
                'order' => $validatedData['order'] ?? 0,
            ]);

            // Update translations
            foreach ($validatedData['name'] as $locale => $name) {
                $attribute->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name' => $name,
                        'description' => $validatedData['description'][$locale] ?? null,
                        'placeholder' => $validatedData['placeholder'][$locale] ?? null,
                        'help_text' => $validatedData['help_text'][$locale] ?? null,
                    ]
                );
            }

            // Update options
            if (isset($validatedData['options']) && in_array($validatedData['type'], ['select', 'multiselect', 'radio', 'checkbox'])) {
                $existingOptionIds = $attribute->options()->pluck('id')->toArray();
                $requestOptionIds = collect($validatedData['options'])->pluck('id')->filter()->toArray();

                // Delete removed options
                $optionsToDelete = array_diff($existingOptionIds, $requestOptionIds);
                if (!empty($optionsToDelete)) {
                    $attribute->options()->whereIn('id', $optionsToDelete)->delete();
                }

                foreach ($validatedData['options'] as $option) {
                    $attributeOption = $attribute->options()->updateOrCreate(
                        ['id' => $option['id'] ?? null],
                        [
                            'value' => $option['value'],
                            'is_default' => $option['is_default'] ?? false,
                            'order' => $option['order'] ?? 0,
                        ]
                    );

                    // Update option translations
                    foreach ($option['label'] as $locale => $label) {
                        $attributeOption->translations()->updateOrCreate(
                            ['locale' => $locale],
                            ['label' => $label]
                        );
                    }
                }
            } else {
                // Delete all options if attribute type is changed
                $attribute->options()->delete();
            }

            return $attribute;
        });
    }

    public function createAttribute(array $validatedData): Attribute
    {
        return DB::transaction(function () use ($validatedData) {
            $attribute = Attribute::create([
                'category_id' => $validatedData['category_id'],
                'type' => $validatedData['type'],
                'validation_rules' => $validatedData['validation_rules'] ?? null,
                'is_required' => $validatedData['is_required'] ?? false,
                'is_filterable' => $validatedData['is_filterable'] ?? false,
                'is_searchable' => $validatedData['is_searchable'] ?? false,
                'show_in_list' => $validatedData['show_in_list'] ?? false,
                'show_in_details' => $validatedData['show_in_details'] ?? false,
                'status' => $validatedData['status'] ?? 'active',
                'order' => $validatedData['order'] ?? 0,
            ]);

            // Save translations
            foreach ($validatedData['name'] as $locale => $name) {
                if ($name) { // Only save if name is provided
                    $attribute->translations()->create([
                        'locale' => $locale,
                        'name' => $name,
                        'description' => $validatedData['description'][$locale] ?? null,
                        'placeholder' => $validatedData['placeholder'][$locale] ?? null,
                        'help_text' => $validatedData['help_text'][$locale] ?? null,
                    ]);
                }
            }

            // Add options if they exist
            if (isset($validatedData['options']) && in_array($validatedData['type'], ['select', 'multiselect', 'radio', 'checkbox'])) {
                foreach ($validatedData['options'] as $option) {
                    $attributeOption = $attribute->options()->create([
                        'value' => $option['value'],
                        'is_default' => $option['is_default'] ?? false,
                        'order' => $option['order'] ?? 0,
                    ]);

                    // Save option translations
                    foreach ($option['label'] as $locale => $label) {
                        if ($label) {
                            $attributeOption->translations()->create([
                                'locale' => $locale,
                                'label' => $label,
                            ]);
                        }
                    }
                }
            }

            return $attribute;
        });
    }

    /**
     * Delete an attribute.
     *
     * @param Attribute $attribute
     * @return void
     * @throws \Exception
     */
    public function deleteAttribute(Attribute $attribute): void
    {
        if ($attribute->products()->exists() || $attribute->categories()->exists()) {
            throw new \Exception(__('messages.attribute_in_use'));
        }

        DB::transaction(function () use ($attribute) {
            $attribute->delete();
        });
    }
}

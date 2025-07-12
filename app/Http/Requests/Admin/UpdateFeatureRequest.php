<?php

namespace App\Http\Requests\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit features');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'icon_type' => 'required|in:font,custom',
            'custom_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'show_in_list' => 'boolean',
            'show_in_details' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*.id' => 'nullable|exists:feature_options,id',
            'options.*.value' => 'required_with:options|string|max:50',
            'options.*.price' => 'nullable|numeric|min:0',
            'options.*.is_default' => 'boolean',
            'options.*.order' => 'nullable|integer|min:0',
        ];

        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = 'nullable|string';
            $rules["help_text.{$language->code}"] = 'nullable|string';
            $rules["options.*.label.{$language->code}"] = "required_with:options|string|max:255";
        }

        return $rules;
    }
}

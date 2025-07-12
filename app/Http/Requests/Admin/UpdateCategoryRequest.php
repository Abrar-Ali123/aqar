<?php

namespace App\Http\Requests\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit categories');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        $rules = [
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'icon_type' => 'required|in:font,custom',
            'custom_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'show_in_menu' => 'boolean',
            'show_in_home' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attributes,id',
        ];

        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = 'nullable|string';
            $rules["meta_title.{$language->code}"] = 'nullable|string|max:255';
            $rules["meta_description.{$language->code}"] = 'nullable|string';
            $rules["slug.{$language->code}"] = "{$required}|string|max:255|unique:category_translations,slug,{$categoryId},category_id";
        }

        return $rules;
    }
}

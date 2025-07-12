<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit projects');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $projectId = $this->route('project')->id;

        $rules = [
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'units_count' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_home' => 'boolean',
            'status' => 'nullable|in:active,inactive,draft',
            'order' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:project_images,id',
            'image_order' => 'nullable|array',
            'image_order.*' => 'integer|min:0',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'array',
            'feature_prices' => 'nullable|array',
            'feature_prices.*' => 'nullable|numeric|min:0',
        ];

        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = 'nullable|string';
            $rules["short_description.{$language->code}"] = 'nullable|string|max:500';
            $rules["address.{$language->code}"] = "{$required}|string|max:255";
            $rules["meta_title.{$language->code}"] = 'nullable|string|max:255';
            $rules["meta_description.{$language->code}"] = 'nullable|string';
            $rules["slug.{$language->code}"] = "{$required}|string|max:255|unique:project_translations,slug,{$projectId},project_id";
        }

        return $rules;
    }
}

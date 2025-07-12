<?php

namespace App\Http\Requests\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create attributes');
    }

    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:text,textarea,number,date,select,multiselect,radio,checkbox,file,color',
            'validation_rules' => 'nullable|string',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'is_searchable' => 'boolean',
            'show_in_list' => 'boolean',
            'show_in_details' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'options' => 'required_if:type,select,multiselect,radio,checkbox|array',
            'options.*.value' => 'required|string|max:50',
            'options.*.is_default' => 'boolean',
            'options.*.order' => 'nullable|integer|min:0',
        ];

        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = 'nullable|string';
            $rules["placeholder.{$language->code}"] = 'nullable|string|max:255';
            $rules["help_text.{$language->code}"] = 'nullable|string';
            $rules["options.*.label.{$language->code}"] = "{$required}|string|max:255";
        }

        return $rules;
    }
}

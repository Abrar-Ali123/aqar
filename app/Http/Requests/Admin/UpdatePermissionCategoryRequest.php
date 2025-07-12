<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Assuming a permission like 'manage permission_categories' or similar exists.
        return auth()->user()->can('manage permission_categories');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:permission_categories,id',
            'order' => 'nullable|integer',
            'translations' => 'required|array',
            'translations.ar' => 'required|string',
            'translations.en' => 'required|string',
        ];
    }
}

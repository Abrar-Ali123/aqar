<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // The permission 'name' (slug) is not updatable to maintain integrity.
        return [
            'category_id' => 'required|exists:permission_categories,id',
            'description' => 'nullable|string',
            'translations' => 'required|array',
            'translations.ar' => 'required|string',
            'translations.en' => 'required|string',
        ];
    }
}

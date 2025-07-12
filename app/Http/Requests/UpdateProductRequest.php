<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // We will handle authorization using Policies later.
        // For now, allow everyone.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'thumbnail' => 'nullable|image|max:2048',
            'media.*' => 'nullable|file|max:10240',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'attributes' => 'nullable|array',
        ];
    }
}

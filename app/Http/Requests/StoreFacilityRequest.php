<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // The authorization logic is moved here from the controller.
        // For now, allow any authenticated user to create a facility.
        // In the future, this could be restricted by a specific permission.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
            'business_category_id' => 'nullable|exists:business_categories,id',
            'business_sector_id' => 'nullable|exists:business_sectors,id',
            'is_active' => 'sometimes|boolean',
        ];
    }
}

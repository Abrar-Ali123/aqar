<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // We get the facility instance from the route to check permissions against it.
        $facility = $this->route('facility');
        return Auth::user()->can('edit', $facility);
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
            'business_category_id' => 'required|exists:business_categories,id',
            'business_sector_id' => 'required|exists:business_sectors,id',
            'is_active' => 'sometimes|boolean',
        ];
    }
}

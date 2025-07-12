<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit languages');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $languageId = $this->route('language')->id;

        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'size:2',
                Rule::unique('languages', 'code')->ignore($languageId),
            ],
            'direction' => 'required|in:ltr,rtl',
            'flag' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_default' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ];
    }
}

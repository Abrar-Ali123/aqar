<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->can('edit users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;

        $rules = [
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $userId],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()], // Password is not required on update
            'is_active' => 'boolean',
            'status' => 'nullable|in:active,inactive,blocked',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'settings' => 'nullable|array',
        ];

        // Add rules for translatable fields
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["first_name.{$language->code}"] = "{$required}|string|max:255";
            $rules["last_name.{$language->code}"] = "{$required}|string|max:255";
            $rules["bio.{$language->code}"] = "nullable|string";
            $rules["address.{$language->code}"] = "nullable|string|max:500";
        }

        return $rules;
    }
}

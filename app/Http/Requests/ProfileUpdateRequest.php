<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone_number' => ['string', 'max:20', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرفاً',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفاً',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone_number.max' => 'رقم الهاتف يجب ألا يتجاوز 20 رقماً',
            'phone_number.unique' => 'رقم الهاتف مستخدم بالفعل',
        ];
    }
}

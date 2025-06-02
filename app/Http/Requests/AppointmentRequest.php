<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => ['required', 'string', 'in:' . implode(',', array_keys(Appointment::TYPES))],
            'appointment_time' => ['required', 'date', 'after:now'],
            'description' => ['required', 'string', 'max:500'],
            'facility_id' => ['required', 'exists:facilities,id'],
            'metadata' => ['sometimes', 'array']
        ];
    }

    public function messages()
    {
        return [
            'type.required' => __('appointments.fields.type') . ' مطلوب',
            'type.in' => __('appointments.fields.type') . ' غير صالح',
            'appointment_time.required' => __('appointments.fields.appointment_time') . ' مطلوب',
            'appointment_time.date' => __('appointments.fields.appointment_time') . ' يجب أن يكون تاريخاً صالحاً',
            'appointment_time.after' => __('appointments.fields.appointment_time') . ' يجب أن يكون بعد الوقت الحالي',
            'description.required' => __('appointments.fields.description') . ' مطلوب',
            'description.max' => __('appointments.fields.description') . ' يجب ألا يتجاوز 500 حرف',
            'facility_id.required' => __('appointments.fields.facility') . ' مطلوب',
            'facility_id.exists' => __('appointments.fields.facility') . ' غير موجود',
            'metadata.array' => 'البيانات الإضافية يجب أن تكون مصفوفة'
        ];
    }
}

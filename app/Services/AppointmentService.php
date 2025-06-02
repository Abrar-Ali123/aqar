<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Facility;
use App\Notifications\AppointmentNotification;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentService
{
    /**
     * إنشاء موعد جديد
     */
    public function create(array $data, array $translations): Appointment
    {
        $appointment = Appointment::create($data);
        $appointment->update(['translations' => $translations]);

        // إرسال إشعار للمستخدم المعني
        if (isset($data['user_id'])) {
            $user = User::find($data['user_id']);
            $user->notify(new AppointmentNotification($appointment));
        }

        return $appointment;
    }

    /**
     * تحديث موعد
     */
    public function update(int $id, array $data, array $translations): Appointment
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);
        $appointment->update(['translations' => $translations]);

        return $appointment;
    }

    /**
     * الحصول على مواعيد المنشأة
     */
    public function getFacilityAppointments(int $facilityId, string $type = null): Collection
    {
        $query = Appointment::forFacility($facilityId)->upcoming();
        
        if ($type) {
            $query->ofType($type);
        }

        return $query->get();
    }

    /**
     * الحصول على مواعيد المستخدم
     */
    public function getUserAppointments(int $userId, string $type = null): Collection
    {
        $query = Appointment::where('user_id', $userId)->upcoming();
        
        if ($type) {
            $query->ofType($type);
        }

        return $query->get();
    }

    /**
     * تغيير حالة الموعد
     */
    public function updateStatus(int $id, string $status): Appointment
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $status]);

        return $appointment;
    }

    /**
     * التحقق من تداخل المواعيد
     */
    public function checkOverlap(Carbon $startTime, int $userId = null, int $facilityId = null): bool
    {
        $query = Appointment::where('appointment_time', $startTime);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }

        return $query->exists();
    }
}

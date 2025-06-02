<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->first()->id;
        $facilityId = DB::table('facilities')->first()->id;

        $appointments = [
            [
                'user_id' => $userId,
                'facility_id' => $facilityId,
                'appointment_time' => now()->addDays(2),
                'status' => 'scheduled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userId,
                'facility_id' => $facilityId,
                'appointment_time' => now()->addDays(5),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($appointments as $appointment) {
            DB::table('appointments')->insert($appointment);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->take(2)->get();

        foreach ($users as $index => $user) {
            DB::table('owners')->insert([
                'user_id' => $user->id,
                'phone' => '966' . str_pad($index + 1, 9, '0', STR_PAD_LEFT),
                'email' => 'owner' . ($index + 1) . '@example.com',
                'national_id' => '1' . str_pad($index + 1, 9, '0', STR_PAD_LEFT),
                'national_id_expiry' => now()->addYears(5),
                'commercial_record' => $index === 0 ? '4030123456' : null,
                'commercial_record_expiry' => $index === 0 ? now()->addYears(3) : null,
                'is_company' => $index === 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

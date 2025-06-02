<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // مستخدم عادي
        $user1 = User::create([
            'phone_number' => '966500000001',
            'email' => 'test1@example.com',
            'password' => Hash::make('12345678'),
            'is_active' => true,
            'email_verified_at' => now(),
            'language_code' => 'ar'
        ]);

        $user1->setTranslation('name', 'ar', 'مستخدم للاختبار');
        $user1->setTranslation('name', 'en', 'Test User');
        $user1->save();

        // مستخدم آخر
        $user2 = User::create([
            'phone_number' => '966500000002',
            'email' => 'test2@example.com',
            'password' => Hash::make('12345678'),
            'is_active' => true,
            'email_verified_at' => now(),
            'language_code' => 'ar'
        ]);

        $user2->setTranslation('name', 'ar', 'مستخدم للاختبار 2');
        $user2->setTranslation('name', 'en', 'Test User 2');
        $user2->save();

        // مستخدم ثالث
        $user3 = User::create([
            'phone_number' => '966500000003',
            'email' => 'test3@example.com',
            'password' => Hash::make('12345678'),
            'is_active' => true,
            'email_verified_at' => now(),
            'language_code' => 'ar'
        ]);

        $user3->setTranslation('name', 'ar', 'مستخدم للاختبار 3');
        $user3->setTranslation('name', 'en', 'Test User 3');
        $user3->save();
    }
}

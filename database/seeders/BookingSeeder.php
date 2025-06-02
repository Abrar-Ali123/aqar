<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->first()->id;
        $productId = DB::table('products')->first()->id;

        $bookings = [
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'payment_method' => 'credit_card',
                'expires_at' => now()->addDays(7),
                'is_confirmed' => true,
                'status' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'payment_method' => 'bank_transfer',
                'expires_at' => now()->addDays(7),
                'is_confirmed' => false,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($bookings as $booking) {
            DB::table('bookings')->insert($booking);
        }
    }
}

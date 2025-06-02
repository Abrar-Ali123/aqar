<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->first()->id;
        $productId = DB::table('products')->first()->id;

        // Add parent comments
        $parentComment1Id = DB::table('product_comments')->insertGetId([
            'product_id' => $productId,
            'user_id' => $userId,
            'parent_id' => null,
            'content' => 'تعليق رئيسي على العقار',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $parentComment2Id = DB::table('product_comments')->insertGetId([
            'product_id' => $productId,
            'user_id' => $userId,
            'parent_id' => null,
            'content' => 'تعليق رئيسي آخر على العقار',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add replies to comments
        DB::table('product_comments')->insert([
            [
                'product_id' => $productId,
                'user_id' => $userId,
                'parent_id' => $parentComment1Id,
                'content' => 'رد على التعليق الأول',
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $productId,
                'user_id' => $userId,
                'parent_id' => $parentComment2Id,
                'content' => 'رد على التعليق الثاني',
                'is_approved' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

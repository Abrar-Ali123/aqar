<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        // التحقق من وجود البيانات المطلوبة
        $user = DB::table('users')->first();
        if (!$user) {
            Log::error('ContractSeeder: لا يوجد مستخدمين في قاعدة البيانات');
            return;
        }

        $product = DB::table('products')->first();
        if (!$product) {
            Log::error('ContractSeeder: لا يوجد منتجات في قاعدة البيانات');
            return;
        }

        $owner = DB::table('owners')->first();
        if (!$owner) {
            Log::error('ContractSeeder: لا يوجد ملاك في قاعدة البيانات');
            return;
        }

        $facility = DB::table('facilities')->first();
        if (!$facility) {
            Log::error('ContractSeeder: لا يوجد منشآت في قاعدة البيانات');
            return;
        }

        $contracts = [
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'owner_id' => $owner->id,
                'facility_id' => $facility->id,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'owner_id' => $owner->id,
                'facility_id' => $facility->id,
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(14),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        try {
            foreach ($contracts as $contract) {
                $contractId = DB::table('contracts')->insertGetId($contract);

                // Add translations
                DB::table('contract_translations')->insert([
                    [
                        'contract_id' => $contractId,
                        'locale' => 'ar',
                        'title' => 'عقد إيجار رقم ' . $contractId,
                        'content' => 'محتوى العقد باللغة العربية',
                        'file' => 'contracts/contract-' . $contractId . '-ar.pdf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'contract_id' => $contractId,
                        'locale' => 'en',
                        'title' => 'Rental Contract #' . $contractId,
                        'content' => 'Contract content in English',
                        'file' => 'contracts/contract-' . $contractId . '-en.pdf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('ContractSeeder: ' . $e->getMessage());
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'logo' => 'banks/anb.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'logo' => 'banks/alrajhi.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($banks as $index => $bank) {
            $bankId = DB::table('banks')->insertGetId($bank);
            
            // Add translations
            DB::table('bank_translations')->insert([
                [
                    'bank_id' => $bankId,
                    'locale' => 'ar',
                    'name' => $index === 0 ? 'البنك العربي الوطني' : 'مصرف الراجحي',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'bank_id' => $bankId,
                    'locale' => 'en',
                    'name' => $index === 0 ? 'Arab National Bank' : 'Al Rajhi Bank',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}

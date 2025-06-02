<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = [
            [
                'email' => 'tenant1@example.com',
                'phone' => '966500000001',
                'identity_number' => '1000000001',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'nationality' => 'Saudi',
                'occupation' => 'Engineer',
                'monthly_income' => 15000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'tenant2@example.com',
                'phone' => '966500000002',
                'identity_number' => '1000000002',
                'date_of_birth' => '1992-02-02',
                'gender' => 'female',
                'nationality' => 'Saudi',
                'occupation' => 'Doctor',
                'monthly_income' => 20000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($tenants as $tenant) {
            $tenantId = DB::table('tenants')->insertGetId($tenant);

            // Add translations
            DB::table('tenant_translations')->insert([
                [
                    'tenant_id' => $tenantId,
                    'locale' => 'ar',
                    'name' => 'مستأجر ' . $tenantId,
                    'description' => 'وصف المستأجر باللغة العربية',
                    'notes' => 'ملاحظات عن المستأجر',
                    'address' => 'عنوان المستأجر باللغة العربية',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tenant_id' => $tenantId,
                    'locale' => 'en',
                    'name' => 'Tenant ' . $tenantId,
                    'description' => 'Tenant description in English',
                    'notes' => 'Notes about the tenant',
                    'address' => 'Tenant address in English',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}

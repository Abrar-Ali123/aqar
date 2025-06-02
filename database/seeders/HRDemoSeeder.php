<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use Carbon\Carbon;

class HRDemoSeeder extends Seeder
{
    public function run()
    {
        // أقسام تجريبية
        $departments = [
            ['id'=>1, 'name'=>'الموارد البشرية'],
            ['id'=>2, 'name'=>'التسويق'],
            ['id'=>3, 'name'=>'المبيعات'],
        ];
        DB::table('departments')->insert($departments);

        // وظائف تجريبية
        $positions = [
            ['id'=>1, 'name'=>'مدير موارد بشرية'],
            ['id'=>2, 'name'=>'مسؤول تسويق'],
            ['id'=>3, 'name'=>'مندوب مبيعات'],
        ];
        DB::table('positions')->insert($positions);

        // مستخدمون وموظفون تجريبيون
        $users = [
            ['id'=>1, 'name'=>'أحمد علي', 'email'=>'ahmed@example.com', 'phone_number'=>'0550000001', 'password'=>Hash::make('password')],
            ['id'=>2, 'name'=>'سارة محمد', 'email'=>'sara@example.com', 'phone_number'=>'0550000002', 'password'=>Hash::make('password')],
            ['id'=>3, 'name'=>'خالد حسن', 'email'=>'khaled@example.com', 'phone_number'=>'0550000003', 'password'=>Hash::make('password')],
        ];
        User::insert($users);

        $profiles = [
            ['user_id'=>1, 'department_id'=>1, 'position_id'=>1, 'hiring_date'=>Carbon::now()->subYears(2), 'job_number'=>'EMP001'],
            ['user_id'=>2, 'department_id'=>2, 'position_id'=>2, 'hiring_date'=>Carbon::now()->subYears(1), 'job_number'=>'EMP002'],
            ['user_id'=>3, 'department_id'=>3, 'position_id'=>3, 'hiring_date'=>Carbon::now()->subMonths(6), 'job_number'=>'EMP003'],
        ];
        Profile::insert($profiles);
    }
}

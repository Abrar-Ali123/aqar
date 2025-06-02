<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->first()->id;

        $tasks = [
            [
                'title' => 'مراجعة العقود',
                'description' => 'مراجعة جميع العقود المنتهية هذا الشهر',
                'user_id' => $userId,
                'status' => 'new',
                'priority' => 'high',
                'category' => 'contracts',
                'due_date' => now()->addDays(7),
                'assigned_to' => json_encode([$userId]),
                'comments' => json_encode([
                    [
                        'user_id' => $userId,
                        'content' => 'يجب البدء في المراجعة في أقرب وقت',
                        'created_at' => now()->toDateTimeString()
                    ]
                ]),
                'attachments' => json_encode([
                    [
                        'name' => 'contracts_list.pdf',
                        'path' => 'attachments/contracts_list.pdf',
                        'type' => 'pdf',
                        'size' => 1024
                    ]
                ]),
                'time_logs' => json_encode([]),
                'subtasks' => json_encode([
                    [
                        'title' => 'تحديد العقود المنتهية',
                        'completed' => false
                    ],
                    [
                        'title' => 'التواصل مع المستأجرين',
                        'completed' => false
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'صيانة المبنى',
                'description' => 'إجراء الصيانة الدورية للمبنى',
                'user_id' => $userId,
                'status' => 'in_progress',
                'priority' => 'medium',
                'category' => 'maintenance',
                'due_date' => now()->addDays(14),
                'assigned_to' => json_encode([$userId]),
                'comments' => json_encode([
                    [
                        'user_id' => $userId,
                        'content' => 'تم البدء في أعمال الصيانة',
                        'created_at' => now()->toDateTimeString()
                    ]
                ]),
                'attachments' => json_encode([
                    [
                        'name' => 'maintenance_checklist.pdf',
                        'path' => 'attachments/maintenance_checklist.pdf',
                        'type' => 'pdf',
                        'size' => 512
                    ]
                ]),
                'time_logs' => json_encode([
                    [
                        'start' => now()->subHours(2)->toDateTimeString(),
                        'end' => now()->toDateTimeString(),
                        'duration' => 7200 // 2 hours in seconds
                    ]
                ]),
                'subtasks' => json_encode([
                    [
                        'title' => 'فحص نظام التكييف',
                        'completed' => true
                    ],
                    [
                        'title' => 'صيانة المصاعد',
                        'completed' => false
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($tasks as $task) {
            DB::table('tasks')->insert($task);
        }
    }
}

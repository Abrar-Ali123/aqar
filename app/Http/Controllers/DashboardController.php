<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function tasksStats()
    {
        // إجمالي المهام
        $total = Task::count();
        // المهام المفتوحة
        $open = Task::where('status', 'open')->count();
        // المهام المكتملة
        $completed = Task::where('status', 'completed')->count();
        // ساعات العمل المنجزة
        $hours = Task::with('timeLogs')->get()->reduce(function($carry, $task) {
            foreach ($task->timeLogs as $log) {
                $carry += ($log->duration ?? 0) / 60; // assuming duration is in minutes
            }
            return $carry;
        }, 0);
        $hours = round($hours, 2);
        // أكثر المستخدمين نشاطًا
        $top_users = User::withCount('tasks')->orderByDesc('tasks_count')->limit(5)->get();
        // توزيع المهام حسب الحالة
        $by_status = Task::select('status')->get()->groupBy('status')->map->count();
        // تحويل مفاتيح الحالة إلى تسميات ودية
        $status_labels = [
            'open' => 'مفتوحة',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتملة',
            'closed' => 'مغلقة',
        ];
        $by_status_labeled = [];
        foreach ($by_status as $key => $count) {
            $by_status_labeled[$status_labels[$key] ?? $key] = $count;
        }
        $stats = [
            'total' => $total,
            'open' => $open,
            'completed' => $completed,
            'hours' => $hours,
            'top_users' => $top_users,
            'by_status' => $by_status_labeled,
        ];
        return view('dashboard.tasks_stats', compact('stats'));
    }
}

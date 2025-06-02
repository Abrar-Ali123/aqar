<?php

namespace App\Services;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * الحصول على إحصائيات عامة
     *
     * @return array
     */
    public function getGeneralStats(): array
    {
        return $this->cacheService->remember('general_stats', function () {
            return [
                'total_facilities' => Facility::count(),
                'active_facilities' => Facility::where('status', 'active')->count(),
                'total_users' => User::count(),
                'active_users' => User::where('status', 'active')->count()
            ];
        }, 30); // تخزين مؤقت لمدة 30 دقيقة
    }

    /**
     * الحصول على إحصائيات المنشآت حسب النوع
     *
     * @return array
     */
    public function getFacilitiesByType(): array
    {
        return $this->cacheService->remember('facilities_by_type', function () {
            return DB::table('facilities')
                ->select('type_id', DB::raw('count(*) as total'))
                ->groupBy('type_id')
                ->get()
                ->toArray();
        }, 60);
    }

    /**
     * الحصول على إحصائيات النمو الشهري
     *
     * @param int $months
     * @return array
     */
    public function getGrowthStats(int $months = 12): array
    {
        $key = "growth_stats_{$months}";
        return $this->cacheService->remember($key, function () use ($months) {
            $stats = [];
            $date = Carbon::now()->subMonths($months);

            $facilities = DB::table('facilities')
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
                ->where('created_at', '>=', $date)
                ->groupBy('month')
                ->get();

            $users = DB::table('users')
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
                ->where('created_at', '>=', $date)
                ->groupBy('month')
                ->get();

            foreach (range(0, $months - 1) as $i) {
                $monthKey = Carbon::now()->subMonths($i)->format('Y-m');
                $stats[$monthKey] = [
                    'facilities' => $facilities->where('month', $monthKey)->first()->total ?? 0,
                    'users' => $users->where('month', $monthKey)->first()->total ?? 0
                ];
            }

            return $stats;
        }, 1440); // تخزين مؤقت لمدة 24 ساعة
    }

    /**
     * الحصول على إحصائيات النشاط
     *
     * @param int $days
     * @return array
     */
    public function getActivityStats(int $days = 30): array
    {
        $key = "activity_stats_{$days}";
        return $this->cacheService->remember($key, function () use ($days) {
            $date = Carbon::now()->subDays($days);

            return [
                'page_visits' => DB::table('page_visits')
                    ->where('created_at', '>=', $date)
                    ->count(),
                'searches' => DB::table('searches')
                    ->where('created_at', '>=', $date)
                    ->count(),
                'bookings' => DB::table('bookings')
                    ->where('created_at', '>=', $date)
                    ->count()
            ];
        }, 60);
    }

    /**
     * الحصول على تقرير أداء النظام
     *
     * @return array
     */
    public function getPerformanceReport(): array
    {
        return $this->cacheService->remember('performance_report', function () {
            return [
                'average_response_time' => DB::table('performance_logs')
                    ->where('created_at', '>=', Carbon::now()->subDay())
                    ->avg('response_time'),
                'error_rate' => DB::table('error_logs')
                    ->where('created_at', '>=', Carbon::now()->subDay())
                    ->count(),
                'cache_hit_rate' => DB::table('cache_logs')
                    ->where('created_at', '>=', Carbon::now()->subDay())
                    ->avg('hit_rate')
            ];
        }, 30);
    }
}

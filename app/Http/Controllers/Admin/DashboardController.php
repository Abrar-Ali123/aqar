<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\User;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // إحصائيات عامة من الكاش
        $stats = Cache::remember('admin.dashboard.stats', now()->addHours(1), function () {
            return [
                'facilities_count' => Facility::count(),
                'users_count' => User::count(),
                'today_bookings' => Booking::whereDate('created_at', Carbon::today())->count(),
                'monthly_revenue' => Payment::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('amount')
            ];
        });

        // إحصائيات المنشآت
        $facilityStats = Cache::remember('admin.dashboard.facility_stats', now()->addHours(1), function () {
            return [
                'active' => Facility::where('status', 'active')->count(),
                'pending' => Facility::where('status', 'pending')->count(),
                'inactive' => Facility::where('status', 'inactive')->count()
            ];
        });

        // الحجوزات الأخيرة
        $recentBookings = Booking::with(['facility', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // المدفوعات الأخيرة
        $recentPayments = Payment::with(['user', 'facility'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'facilityStats',
            'recentBookings',
            'recentPayments'
        ));
    }

    /**
     * إحصائيات مخصصة حسب الفترة
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customStats(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $stats = [
            'bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'revenue' => Payment::whereBetween('created_at', [$startDate, $endDate])->sum('amount'),
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_facilities' => Facility::whereBetween('created_at', [$startDate, $endDate])->count()
        ];

        return response()->json($stats);
    }

    /**
     * تحديث الإحصائيات
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshStats()
    {
        Cache::forget('admin.dashboard.stats');
        Cache::forget('admin.dashboard.facility_stats');

        return response()->json(['message' => 'تم تحديث الإحصائيات بنجاح']);
    }
}

<?php

namespace App\Services\Analytics;

use App\Models\Facility;
use App\Models\FacilityAnalytics;
use Illuminate\Support\Facades\Cache;

class AnalyticsManager
{
    /**
     * تحليل أداء المنشأة
     */
    public function analyzeFacilityPerformance(Facility $facility)
    {
        return Cache::remember("facility_performance_{$facility->id}", 3600, function () use ($facility) {
            return [
                'traffic' => $this->analyzeTraffic($facility),
                'engagement' => $this->analyzeEngagement($facility),
                'conversion' => $this->analyzeConversion($facility),
                'revenue' => $this->analyzeRevenue($facility),
                'recommendations' => $this->generateRecommendations($facility)
            ];
        });
    }

    /**
     * تحليل حركة الزوار
     */
    private function analyzeTraffic(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        return [
            'total_visits' => $analytics->count(),
            'unique_visitors' => $analytics->unique('visitor_id')->count(),
            'average_duration' => $analytics->avg('duration'),
            'bounce_rate' => $this->calculateBounceRate($analytics),
            'traffic_sources' => $this->analyzeTrafficSources($analytics),
            'peak_hours' => $this->analyzePeakHours($analytics),
            'device_breakdown' => $this->analyzeDevices($analytics)
        ];
    }

    /**
     * تحليل التفاعل
     */
    private function analyzeEngagement(Facility $facility)
    {
        return [
            'page_views' => $this->analyzePageViews($facility),
            'interactions' => $this->analyzeUserInteractions($facility),
            'popular_content' => $this->analyzePopularContent($facility),
            'user_behavior' => $this->analyzeUserBehavior($facility),
            'search_analysis' => $this->analyzeSearchBehavior($facility)
        ];
    }

    /**
     * تحليل التحويل
     */
    private function analyzeConversion(Facility $facility)
    {
        return [
            'overall_rate' => $this->calculateConversionRate($facility),
            'funnel_analysis' => $this->analyzeFunnel($facility),
            'goal_completion' => $this->analyzeGoalCompletion($facility),
            'abandonment_rate' => $this->calculateAbandonmentRate($facility)
        ];
    }

    /**
     * تحليل الإيرادات
     */
    private function analyzeRevenue(Facility $facility)
    {
        return [
            'total_revenue' => $this->calculateTotalRevenue($facility),
            'average_order_value' => $this->calculateAverageOrderValue($facility),
            'revenue_by_product' => $this->analyzeRevenueByProduct($facility),
            'revenue_trends' => $this->analyzeRevenueTrends($facility)
        ];
    }

    /**
     * توليد توصيات تحسين
     */
    private function generateRecommendations(Facility $facility)
    {
        $recommendations = [];

        // تحليل حركة المرور
        $traffic = $this->analyzeTraffic($facility);
        if ($traffic['bounce_rate'] > 50) {
            $recommendations[] = [
                'type' => 'traffic',
                'priority' => 'high',
                'title' => 'تحسين معدل الارتداد',
                'description' => 'معدل الارتداد مرتفع. نوصي بتحسين تجربة المستخدم وسرعة تحميل الصفحة.'
            ];
        }

        // تحليل التفاعل
        $engagement = $this->analyzeEngagement($facility);
        if ($engagement['page_views']['average_time'] < 60) {
            $recommendations[] = [
                'type' => 'engagement',
                'priority' => 'medium',
                'title' => 'زيادة وقت التفاعل',
                'description' => 'متوسط وقت التفاعل منخفض. جرب إضافة محتوى أكثر جاذبية وتفاعلية.'
            ];
        }

        // تحليل التحويل
        $conversion = $this->analyzeConversion($facility);
        if ($conversion['overall_rate'] < 2) {
            $recommendations[] = [
                'type' => 'conversion',
                'priority' => 'high',
                'title' => 'تحسين معدل التحويل',
                'description' => 'معدل التحويل منخفض. نوصي بمراجعة مسار التحويل وتبسيط عملية الشراء.'
            ];
        }

        return $recommendations;
    }

    /**
     * حساب معدل الارتداد
     */
    private function calculateBounceRate($analytics)
    {
        $singlePageVisits = $analytics->filter(function ($visit) {
            return $visit->interaction_data['page_views'] === 1;
        })->count();

        return $analytics->count() > 0 
            ? ($singlePageVisits / $analytics->count()) * 100 
            : 0;
    }

    /**
     * تحليل مصادر الزيارات
     */
    private function analyzeTrafficSources($analytics)
    {
        return $analytics->groupBy('referrer')
            ->map(function ($group) use ($analytics) {
                return [
                    'count' => $group->count(),
                    'percentage' => ($group->count() / $analytics->count()) * 100
                ];
            });
    }

    /**
     * تحليل ساعات الذروة
     */
    private function analyzePeakHours($analytics)
    {
        return $analytics->groupBy(function ($visit) {
            return $visit->created_at->format('H');
        })->map(function ($group) use ($analytics) {
            return [
                'count' => $group->count(),
                'percentage' => ($group->count() / $analytics->count()) * 100
            ];
        });
    }

    /**
     * تحليل الأجهزة المستخدمة
     */
    private function analyzeDevices($analytics)
    {
        return $analytics->groupBy('device_type')
            ->map(function ($group) use ($analytics) {
                return [
                    'count' => $group->count(),
                    'percentage' => ($group->count() / $analytics->count()) * 100
                ];
            });
    }

    /**
     * تحليل مشاهدات الصفحات
     */
    private function analyzePageViews(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        return [
            'total_views' => $analytics->sum('page_views'),
            'average_time' => $analytics->avg('time_on_page'),
            'popular_pages' => $this->getPopularPages($analytics),
            'exit_pages' => $this->getExitPages($analytics)
        ];
    }

    /**
     * تحليل تفاعلات المستخدم
     */
    private function analyzeUserInteractions(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        return [
            'clicks' => $this->analyzeClicks($analytics),
            'scrolls' => $this->analyzeScrolls($analytics),
            'forms' => $this->analyzeFormInteractions($analytics),
            'buttons' => $this->analyzeButtonClicks($analytics)
        ];
    }

    /**
     * تحليل المحتوى الشائع
     */
    private function analyzePopularContent(Facility $facility)
    {
        return [
            'products' => $this->getPopularProducts($facility),
            'categories' => $this->getPopularCategories($facility),
            'pages' => $this->getPopularPages($facility)
        ];
    }

    /**
     * تحليل سلوك المستخدم
     */
    private function analyzeUserBehavior(Facility $facility)
    {
        return [
            'flow' => $this->getUserFlow($facility),
            'paths' => $this->getPopularPaths($facility),
            'interests' => $this->getUserInterests($facility)
        ];
    }

    /**
     * تحليل سلوك البحث
     */
    private function analyzeSearchBehavior(Facility $facility)
    {
        return [
            'popular_terms' => $this->getPopularSearchTerms($facility),
            'no_results' => $this->getNoResultsSearches($facility),
            'search_refinements' => $this->getSearchRefinements($facility)
        ];
    }
}

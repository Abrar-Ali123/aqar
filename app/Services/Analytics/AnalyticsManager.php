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
     * حساب معدل التحويل
     */
    private function calculateConversionRate(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $totalVisits = $analytics->count();
        $conversions = 0;

        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['conversion']) && $data['conversion']['completed']) {
                $conversions++;
            }
        }

        return [
            'rate' => $totalVisits > 0 ? ($conversions / $totalVisits) * 100 : 0,
            'total_conversions' => $conversions,
            'total_visits' => $totalVisits
        ];
    }

    /**
     * تحليل مسار التحويل
     */
    private function analyzeFunnel(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $funnel = [
            'view' => 0,
            'interest' => 0,
            'consideration' => 0,
            'intent' => 0,
            'conversion' => 0
        ];

        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['funnel'])) {
                foreach ($data['funnel'] as $stage => $reached) {
                    if ($reached && isset($funnel[$stage])) {
                        $funnel[$stage]++;
                    }
                }
            }
        }

        return collect($funnel)->map(function ($count) use ($analytics) {
            return [
                'count' => $count,
                'percentage' => $analytics->count() > 0 ? ($count / $analytics->count()) * 100 : 0
            ];
        })->all();
    }

    /**
     * تحليل إكمال الأهداف
     */
    private function analyzeGoalCompletion(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $goals = [];
        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['goals'])) {
                foreach ($data['goals'] as $goal => $details) {
                    if (!isset($goals[$goal])) {
                        $goals[$goal] = [
                            'attempts' => 0,
                            'completions' => 0
                        ];
                    }
                    $goals[$goal]['attempts']++;
                    if ($details['completed']) {
                        $goals[$goal]['completions']++;
                    }
                }
            }
        }

        return collect($goals)
            ->map(function ($data, $goal) {
                return [
                    'goal' => $goal,
                    'attempts' => $data['attempts'],
                    'completions' => $data['completions'],
                    'completion_rate' => $data['attempts'] > 0 ? ($data['completions'] / $data['attempts']) * 100 : 0
                ];
            })
            ->values()
            ->all();
    }

    /**
     * حساب معدل التخلي
     */
    private function calculateAbandonmentRate(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $totalSessions = $analytics->count();
        $abandonedSessions = 0;

        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['session']) && $data['session']['abandoned']) {
                $abandonedSessions++;
            }
        }

        return [
            'rate' => $totalSessions > 0 ? ($abandonedSessions / $totalSessions) * 100 : 0,
            'total_abandoned' => $abandonedSessions,
            'total_sessions' => $totalSessions
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
     * حساب إجمالي الإيرادات
     */
    private function calculateTotalRevenue(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $totalRevenue = 0;
        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['revenue'])) {
                $totalRevenue += $data['revenue']['amount'] ?? 0;
            }
        }

        return [
            'amount' => $totalRevenue,
            'currency' => 'SAR',
            'period' => '30 days'
        ];
    }

    /**
     * حساب متوسط قيمة الطلب
     */
    private function calculateAverageOrderValue(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $totalRevenue = 0;
        $orderCount = 0;

        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['revenue']) && !empty($data['revenue']['order_completed'])) {
                $totalRevenue += $data['revenue']['amount'] ?? 0;
                $orderCount++;
            }
        }

        return [
            'average' => $orderCount > 0 ? $totalRevenue / $orderCount : 0,
            'total_orders' => $orderCount,
            'currency' => 'SAR'
        ];
    }

    /**
     * تحليل الإيرادات حسب المنتج
     */
    private function analyzeRevenueByProduct(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $productRevenue = [];
        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['revenue']['products'])) {
                foreach ($data['revenue']['products'] as $productId => $details) {
                    if (!isset($productRevenue[$productId])) {
                        $productRevenue[$productId] = [
                            'revenue' => 0,
                            'units_sold' => 0
                        ];
                    }
                    $productRevenue[$productId]['revenue'] += $details['amount'] ?? 0;
                    $productRevenue[$productId]['units_sold'] += $details['quantity'] ?? 0;
                }
            }
        }

        return collect($productRevenue)
            ->map(function ($data, $productId) use ($analytics) {
                $totalRevenue = collect($analytics)
                    ->sum(function ($analytic) {
                        $data = json_decode($analytic->interaction_data, true);
                        return $data['revenue']['amount'] ?? 0;
                    });

                return [
                    'product_id' => $productId,
                    'revenue' => $data['revenue'],
                    'units_sold' => $data['units_sold'],
                    'percentage' => $totalRevenue > 0 ? ($data['revenue'] / $totalRevenue) * 100 : 0
                ];
            })
            ->sortByDesc('revenue')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * تحليل اتجاهات الإيرادات
     */
    private function analyzeRevenueTrends(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get()
            ->groupBy(function ($analytic) {
                return $analytic->created_at->format('Y-m-d');
            });

        return $analytics->map(function ($dayAnalytics, $date) {
            $dailyRevenue = 0;
            $orderCount = 0;

            foreach ($dayAnalytics as $analytic) {
                $data = json_decode($analytic->interaction_data, true);
                if (!empty($data['revenue'])) {
                    $dailyRevenue += $data['revenue']['amount'] ?? 0;
                    if (!empty($data['revenue']['order_completed'])) {
                        $orderCount++;
                    }
                }
            }

            return [
                'date' => $date,
                'revenue' => $dailyRevenue,
                'orders' => $orderCount,
                'average_order_value' => $orderCount > 0 ? $dailyRevenue / $orderCount : 0
            ];
        })->values()->all();
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
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        return [
            'popular_products' => $this->getPopularProducts($facility),
            'popular_categories' => $this->getPopularCategories($facility),
            'popular_pages' => $this->getPopularPages($analytics)
        ];
    }

    /**
     * تحليل سلوك المستخدم
     */
    private function analyzeUserBehavior(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $behaviors = [];
        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['behavior'])) {
                foreach ($data['behavior'] as $action => $details) {
                    if (!isset($behaviors[$action])) {
                        $behaviors[$action] = [
                            'count' => 0,
                            'duration' => 0,
                            'engagement_score' => 0
                        ];
                    }
                    $behaviors[$action]['count']++;
                    $behaviors[$action]['duration'] += $details['duration'] ?? 0;
                    $behaviors[$action]['engagement_score'] += $details['engagement'] ?? 0;
                }
            }
        }

        return collect($behaviors)
            ->map(function ($data, $action) use ($analytics) {
                return [
                    'action' => $action,
                    'count' => $data['count'],
                    'percentage' => ($data['count'] / $analytics->count()) * 100,
                    'avg_duration' => $data['count'] > 0 ? $data['duration'] / $data['count'] : 0,
                    'avg_engagement' => $data['count'] > 0 ? $data['engagement_score'] / $data['count'] : 0
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->all();
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

    /**
     * الحصول على مصطلحات البحث الشائعة
     */
    private function getPopularSearchTerms(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $searchTerms = [];
        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['search']['terms'])) {
                foreach ($data['search']['terms'] as $term => $details) {
                    if (!isset($searchTerms[$term])) {
                        $searchTerms[$term] = [
                            'count' => 0,
                            'results_count' => 0
                        ];
                    }
                    $searchTerms[$term]['count']++;
                    $searchTerms[$term]['results_count'] += $details['results'] ?? 0;
                }
            }
        }

        return collect($searchTerms)
            ->map(function ($data, $term) use ($analytics) {
                return [
                    'term' => $term,
                    'count' => $data['count'],
                    'percentage' => ($data['count'] / $analytics->count()) * 100,
                    'avg_results' => $data['count'] > 0 ? $data['results_count'] / $data['count'] : 0
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * الحصول على عمليات البحث بدون نتائج
     */
    private function getNoResultsSearches(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $noResults = [];
        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['search']['no_results'])) {
                foreach ($data['search']['no_results'] as $term) {
                    if (!isset($noResults[$term])) {
                        $noResults[$term] = 0;
                    }
                    $noResults[$term]++;
                }
            }
        }

        return collect($noResults)
            ->map(function ($count, $term) use ($analytics) {
                return [
                    'term' => $term,
                    'count' => $count,
                    'percentage' => ($count / $analytics->count()) * 100
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * الحصول على تحسينات البحث
     */
    private function getSearchRefinements(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $refinements = [];
        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['search']['refinements'])) {
                foreach ($data['search']['refinements'] as $type => $details) {
                    if (!isset($refinements[$type])) {
                        $refinements[$type] = [
                            'count' => 0,
                            'success_count' => 0
                        ];
                    }
                    $refinements[$type]['count']++;
                    $refinements[$type]['success_count'] += $details['success'] ?? 0;
                }
            }
        }

        return collect($refinements)
            ->map(function ($data, $type) use ($analytics) {
                return [
                    'type' => $type,
                    'count' => $data['count'],
                    'percentage' => ($data['count'] / $analytics->count()) * 100,
                    'success_rate' => $data['count'] > 0 ? ($data['success_count'] / $data['count']) * 100 : 0
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * الحصول على الصفحات الأكثر شعبية
     */
    private function getPopularPages($analytics)
    {
        $pageViews = collect();
        foreach ($analytics as $analytic) {
            $data = json_decode($analytic->interaction_data, true);
            if (!empty($data['page_url'])) {
                $url = $data['page_url'];
                if (!$pageViews->has($url)) {
                    $pageViews[$url] = [
                        'views' => 0,
                        'time_spent' => 0
                    ];
                }
                $pageViews[$url]['views'] += $analytic->page_views;
                $pageViews[$url]['time_spent'] += $analytic->time_on_page;
            }
        }

        return $pageViews
            ->map(function ($data, $url) use ($analytics) {
                $totalViews = $analytics->sum('page_views');
                return [
                    'url' => $url,
                    'views' => $data['views'],
                    'percentage' => $totalViews > 0 ? ($data['views'] / $totalViews) * 100 : 0,
                    'average_time' => $data['views'] > 0 ? $data['time_spent'] / $data['views'] : 0
                ];
            })
            ->sortByDesc('views')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * الحصول على صفحات الخروج
     */
    private function getExitPages($analytics)
    {
        return $analytics
            ->groupBy('exit_page')
            ->map(function ($group) use ($analytics) {
                return [
                    'url' => $group->first()->exit_page,
                    'count' => $group->count(),
                    'percentage' => ($group->count() / $analytics->count()) * 100,
                    'bounce_rate' => $this->calculateBounceRate($group)
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * تحليل النقرات
     */
    private function analyzeClicks($analytics)
    {
        return $analytics
            ->groupBy('click_target')
            ->map(function ($group) use ($analytics) {
                return [
                    'target' => $group->first()->click_target,
                    'count' => $group->count(),
                    'percentage' => ($group->count() / $analytics->count()) * 100
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * تحليل التمرير
     */
    private function analyzeScrolls($analytics)
    {
        return [
            'average_depth' => $analytics->avg('scroll_depth'),
            'max_depth' => $analytics->max('scroll_depth'),
            'scroll_patterns' => $analytics
                ->groupBy('scroll_pattern')
                ->map(function ($group) use ($analytics) {
                    return [
                        'pattern' => $group->first()->scroll_pattern,
                        'count' => $group->count(),
                        'percentage' => ($group->count() / $analytics->count()) * 100
                    ];
                })
                ->sortByDesc('count')
                ->take(5)
                ->values()
                ->all()
        ];
    }

    /**
     * تحليل تفاعلات النماذج
     */
    private function analyzeFormInteractions($analytics)
    {
        return $analytics
            ->groupBy('form_id')
            ->map(function ($group) use ($analytics) {
                return [
                    'form_id' => $group->first()->form_id,
                    'submissions' => $group->where('form_submitted', true)->count(),
                    'abandonments' => $group->where('form_submitted', false)->count(),
                    'completion_rate' => ($group->where('form_submitted', true)->count() / $group->count()) * 100
                ];
            })
            ->sortByDesc('submissions')
            ->values()
            ->all();
    }

    /**
     * تحليل نقرات الأزرار
     */
    private function analyzeButtonClicks($analytics)
    {
        return $analytics
            ->groupBy('button_id')
            ->map(function ($group) use ($analytics) {
                return [
                    'button_id' => $group->first()->button_id,
                    'clicks' => $group->count(),
                    'percentage' => ($group->count() / $analytics->count()) * 100
                ];
            })
            ->sortByDesc('clicks')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * الحصول على المنتجات الأكثر شعبية
     */
    private function getPopularProducts(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        // تجميع حسب المنتجات المشاهدة
        $productViews = collect();
        foreach ($analytics as $analytic) {
            if (!empty($analytic->viewed_products)) {
                $products = json_decode($analytic->viewed_products, true);
                foreach ($products as $productId => $details) {
                    if (!$productViews->has($productId)) {
                        $productViews[$productId] = [
                            'views' => 0,
                            'time_spent' => 0,
                            'conversions' => 0
                        ];
                    }
                    $productViews[$productId]['views']++;
                    $productViews[$productId]['time_spent'] += $details['time_spent'] ?? 0;
                    $productViews[$productId]['conversions'] += $details['converted'] ?? 0;
                }
            }
        }

        return $productViews
            ->map(function ($data, $productId) use ($productViews) {
                $totalViews = $productViews->sum('views');
                return [
                    'product_id' => $productId,
                    'views' => $data['views'],
                    'percentage' => $totalViews > 0 ? ($data['views'] / $totalViews) * 100 : 0,
                    'avg_time_spent' => $data['views'] > 0 ? $data['time_spent'] / $data['views'] : 0,
                    'conversion_rate' => $data['views'] > 0 ? ($data['conversions'] / $data['views']) * 100 : 0
                ];
            })
            ->sortByDesc('views')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * الحصول على الفئات الأكثر شعبية
     */
    private function getPopularCategories(Facility $facility)
    {
        $analytics = FacilityAnalytics::where('facility_id', $facility->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        // تجميع حسب الفئات المشاهدة
        $categoryViews = collect();
        foreach ($analytics as $analytic) {
            if (!empty($analytic->viewed_categories)) {
                $categories = json_decode($analytic->viewed_categories, true);
                foreach ($categories as $categoryId => $details) {
                    if (!$categoryViews->has($categoryId)) {
                        $categoryViews[$categoryId] = [
                            'views' => 0,
                            'time_spent' => 0,
                            'engagement_score' => 0
                        ];
                    }
                    $categoryViews[$categoryId]['views']++;
                    $categoryViews[$categoryId]['time_spent'] += $details['time_spent'] ?? 0;
                    $categoryViews[$categoryId]['engagement_score'] += $details['engagement_score'] ?? 0;
                }
            }
        }

        return $categoryViews
            ->map(function ($data, $categoryId) use ($categoryViews) {
                $totalViews = $categoryViews->sum('views');
                return [
                    'category_id' => $categoryId,
                    'views' => $data['views'],
                    'percentage' => $totalViews > 0 ? ($data['views'] / $totalViews) * 100 : 0,
                    'avg_time_spent' => $data['views'] > 0 ? $data['time_spent'] / $data['views'] : 0,
                    'avg_engagement' => $data['views'] > 0 ? $data['engagement_score'] / $data['views'] : 0
                ];
            })
            ->sortByDesc('views')
            ->take(10)
            ->values()
            ->all();
    }
}

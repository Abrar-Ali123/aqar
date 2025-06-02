<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Location;
use App\Models\Attribute;
use App\Models\InventoryMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryAnalytics
{
    // تحليل معدل الاستهلاك
    public function analyzeConsumptionRate(
        Product $product,
        Attribute $attribute,
        string $value,
        Location $location,
        int $days = 30
    ): array {
        $startDate = Carbon::now()->subDays($days);
        
        $movements = InventoryMovement::where('product_id', $product->id)
            ->where('attribute_id', $attribute->id)
            ->where('attribute_value', $value)
            ->where(function ($query) use ($location) {
                $query->where('from_location_id', $location->id)
                    ->orWhere('to_location_id', $location->id);
            })
            ->where('created_at', '>=', $startDate)
            ->get();

        $consumption = 0;
        foreach ($movements as $movement) {
            if ($movement->from_location_id === $location->id) {
                $consumption += $movement->quantity;
            } else {
                $consumption -= $movement->quantity;
            }
        }

        $dailyAverage = $consumption / $days;
        $weeklyAverage = $dailyAverage * 7;
        $monthlyAverage = $dailyAverage * 30;

        return [
            'total_consumption' => $consumption,
            'daily_average' => $dailyAverage,
            'weekly_average' => $weeklyAverage,
            'monthly_average' => $monthlyAverage
        ];
    }

    // التنبؤ بنقطة إعادة الطلب
    public function predictReorderPoint(
        Product $product,
        Attribute $attribute,
        string $value,
        Location $location,
        int $leadTimeDays,
        float $safetyStockFactor = 1.5
    ): array {
        $analysis = $this->analyzeConsumptionRate($product, $attribute, $value, $location);
        
        $dailyConsumption = $analysis['daily_average'];
        $leadTimeConsumption = $dailyConsumption * $leadTimeDays;
        $standardDeviation = $this->calculateConsumptionStandardDeviation(
            $product,
            $attribute,
            $value,
            $location
        );

        $safetyStock = $standardDeviation * $safetyStockFactor;
        $reorderPoint = $leadTimeConsumption + $safetyStock;

        return [
            'reorder_point' => $reorderPoint,
            'safety_stock' => $safetyStock,
            'lead_time_consumption' => $leadTimeConsumption,
            'standard_deviation' => $standardDeviation
        ];
    }

    // حساب الانحراف المعياري للاستهلاك
    protected function calculateConsumptionStandardDeviation(
        Product $product,
        Attribute $attribute,
        string $value,
        Location $location,
        int $days = 30
    ): float {
        $dailyConsumption = DB::table('inventory_movements')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(quantity) as total'))
            ->where('product_id', $product->id)
            ->where('attribute_id', $attribute->id)
            ->where('attribute_value', $value)
            ->where('from_location_id', $location->id)
            ->whereDate('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->get();

        if ($dailyConsumption->isEmpty()) {
            return 0;
        }

        $mean = $dailyConsumption->avg('total');
        $variance = $dailyConsumption->reduce(function ($carry, $item) use ($mean) {
            return $carry + pow($item->total - $mean, 2);
        }, 0) / $dailyConsumption->count();

        return sqrt($variance);
    }

    // تحليل الموسمية
    public function analyzeSeasonality(
        Product $product,
        Attribute $attribute,
        string $value,
        Location $location,
        int $months = 12
    ): array {
        $startDate = Carbon::now()->subMonths($months);
        
        $monthlyConsumption = DB::table('inventory_movements')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(quantity) as total')
            )
            ->where('product_id', $product->id)
            ->where('attribute_id', $attribute->id)
            ->where('attribute_value', $value)
            ->where('from_location_id', $location->id)
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $seasonalityIndex = [];
        $monthlyAverage = $monthlyConsumption->avg('total');

        foreach ($monthlyConsumption as $consumption) {
            $month = Carbon::create($consumption->year, $consumption->month, 1)->format('Y-m');
            $seasonalityIndex[$month] = $monthlyAverage > 0 ? 
                $consumption->total / $monthlyAverage : 0;
        }

        return [
            'monthly_consumption' => $monthlyConsumption,
            'seasonality_index' => $seasonalityIndex,
            'peak_month' => array_search(max($seasonalityIndex), $seasonalityIndex),
            'low_month' => array_search(min($seasonalityIndex), $seasonalityIndex)
        ];
    }

    // تحليل تكلفة المخزون
    public function analyzeInventoryCost(
        Product $product,
        Attribute $attribute,
        string $value,
        Location $location,
        float $holdingCostPercentage = 0.2,
        float $orderingCost = 100
    ): array {
        $inventory = $product->getStockInLocation($location, $attribute, $value)->first();
        if (!$inventory) {
            return [
                'holding_cost' => 0,
                'ordering_cost' => 0,
                'total_cost' => 0,
                'eoq' => 0
            ];
        }

        $analysis = $this->analyzeConsumptionRate($product, $attribute, $value, $location);
        $annualDemand = $analysis['daily_average'] * 365;
        $unitCost = $product->cost ?? 0;

        // حساب تكلفة الاحتفاظ بالمخزون
        $holdingCost = $inventory->quantity * $unitCost * $holdingCostPercentage;

        // حساب الكمية الاقتصادية للطلب (EOQ)
        $eoq = sqrt((2 * $annualDemand * $orderingCost) / ($unitCost * $holdingCostPercentage));

        // حساب عدد الطلبات السنوية المثالي
        $optimalOrdersPerYear = $annualDemand / $eoq;

        // حساب إجمالي تكلفة الطلبات السنوية
        $annualOrderingCost = $optimalOrdersPerYear * $orderingCost;

        return [
            'holding_cost' => $holdingCost,
            'ordering_cost' => $annualOrderingCost,
            'total_cost' => $holdingCost + $annualOrderingCost,
            'eoq' => $eoq,
            'optimal_orders_per_year' => $optimalOrdersPerYear,
            'optimal_order_cycle_days' => 365 / $optimalOrdersPerYear
        ];
    }

    // توصيات تحسين المخزون
    public function getInventoryOptimizationRecommendations(
        Product $product,
        Attribute $attribute,
        string $value,
        Location $location
    ): array {
        $inventory = $product->getStockInLocation($location, $attribute, $value)->first();
        $analysis = $this->analyzeConsumptionRate($product, $attribute, $value, $location);
        $costs = $this->analyzeInventoryCost($product, $attribute, $value, $location);
        $seasonality = $this->analyzeSeasonality($product, $attribute, $value, $location);

        $recommendations = [];

        // تحليل مستوى المخزون
        if ($inventory) {
            $daysOfStock = $analysis['daily_average'] > 0 ? 
                $inventory->quantity / $analysis['daily_average'] : 0;

            if ($daysOfStock > 90) {
                $recommendations[] = [
                    'type' => 'overstock',
                    'message' => 'المخزون الحالي يكفي لأكثر من 90 يوم. يُنصح بتخفيض كمية الطلب.',
                    'action' => 'reduce_order_quantity',
                    'priority' => 'high'
                ];
            } elseif ($daysOfStock < 7) {
                $recommendations[] = [
                    'type' => 'low_stock',
                    'message' => 'المخزون الحالي منخفض جداً. يُنصح بإعادة الطلب فوراً.',
                    'action' => 'reorder_now',
                    'priority' => 'urgent'
                ];
            }
        }

        // تحليل الموسمية
        if (!empty($seasonality['seasonality_index'])) {
            $currentMonth = Carbon::now()->format('Y-m');
            $currentIndex = $seasonality['seasonality_index'][$currentMonth] ?? 1;

            if ($currentIndex > 1.2) {
                $recommendations[] = [
                    'type' => 'seasonality',
                    'message' => 'نحن في موسم ذروة الطلب. يُنصح بزيادة المخزون.',
                    'action' => 'increase_stock',
                    'priority' => 'medium'
                ];
            }
        }

        // تحليل التكلفة
        if ($costs['eoq'] > 0) {
            $recommendations[] = [
                'type' => 'cost_optimization',
                'message' => sprintf(
                    'الكمية الاقتصادية المثالية للطلب هي %.2f وحدة كل %.0f يوم',
                    $costs['eoq'],
                    $costs['optimal_order_cycle_days']
                ),
                'action' => 'optimize_order_quantity',
                'priority' => 'medium'
            ];
        }

        return $recommendations;
    }
}

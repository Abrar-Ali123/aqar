<?php
namespace App\Services;

class ReportService
{
    public function salesMonthly()
    {
        // مثال: جلب تقارير المبيعات الشهرية
        return [
            ['month' => '2025-01', 'sales' => 1000],
            ['month' => '2025-02', 'sales' => 1800],
        ];
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class ReportController extends Controller
{
    public function salesMonthly(Request $request)
    {
        $sales = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total_sales')
            ->groupBy('month')
            ->get();
        return view('dashboard.reports.sales', compact('sales'));
    }
}

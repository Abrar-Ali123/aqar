<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentChartsController extends Controller
{
    public function gatewayChart()
    {
        $data = PaymentTransaction::selectRaw('gateway, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('gateway')
            ->get();
        return response()->json($data);
    }
    public function statusChart()
    {
        $data = PaymentTransaction::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        return response()->json($data);
    }
    public function timelineChart()
    {
        $data = PaymentTransaction::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        return response()->json($data);
    }
}

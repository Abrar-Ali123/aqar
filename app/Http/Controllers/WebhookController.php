<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\Subscription;

class WebhookController extends Controller
{
    public function handlePayment(Request $request)
    {
        // سجل كل عملية دفع أو استرداد
        \Log::info('Webhook Payment', $request->all());
        // معالجة إضافية حسب الحاجة
        return response()->json(['status' => 'ok']);
    }
    public function handleSubscription(Request $request)
    {
        \Log::info('Webhook Subscription', $request->all());
        return response()->json(['status' => 'ok']);
    }
}

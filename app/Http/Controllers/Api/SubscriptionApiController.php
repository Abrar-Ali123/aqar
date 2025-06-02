<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionApiController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        $amount = $request->input('amount');
        $interval = $request->input('interval', 'month');
        $currency = $request->input('currency', 'SAR');
        $sub = Subscription::create([
            'user_id' => $user ? $user->id : null,
            'gateway' => 'api',
            'subscription_id' => uniqid('api_sub_'),
            'status' => 'active',
            'amount' => $amount,
            'currency' => $currency,
            'interval' => $interval,
            'started_at' => now(),
            'details' => [],
        ]);
        return response()->json(['status' => 'created', 'subscription' => $sub]);
    }
    public function cancel($id)
    {
        $sub = Subscription::findOrFail($id);
        $sub->status = 'canceled';
        $sub->ends_at = now();
        $sub->save();
        return response()->json(['status' => 'canceled']);
    }
}

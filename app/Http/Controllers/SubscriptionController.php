<?php
namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;
use Stripe\Customer as StripeCustomer;

class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        $user = auth()->user();
        $amount = $request->input('amount');
        $interval = $request->input('interval', 'month');
        $currency = $request->input('currency', config('payment.gateways.stripe.currency', 'SAR'));
        Stripe::setApiKey(config('payment.gateways.stripe.api_key'));
        $customer = StripeCustomer::create([
            'email' => $user->email,
            'name' => $user->name,
        ]);
        $stripeSub = StripeSubscription::create([
            'customer' => $customer->id,
            'items' => [[
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => 'اشتراك شهري',
                    ],
                    'unit_amount' => intval($amount * 100),
                    'recurring' => [ 'interval' => $interval ],
                ],
            ]],
        ]);
        $sub = Subscription::create([
            'user_id' => $user->id,
            'gateway' => 'stripe',
            'subscription_id' => $stripeSub->id,
            'status' => $stripeSub->status,
            'amount' => $amount,
            'currency' => $currency,
            'interval' => $interval,
            'started_at' => now(),
            'details' => [ 'stripe' => $stripeSub ]
        ]);
        return response()->json(['status' => 'success', 'subscription' => $sub]);
    }

    public function cancel($id)
    {
        $sub = Subscription::findOrFail($id);
        Stripe::setApiKey(config('payment.gateways.stripe.api_key'));
        $stripeSub = StripeSubscription::retrieve($sub->subscription_id);
        $stripeSub->cancel();
        $sub->status = 'canceled';
        $sub->ends_at = now();
        $sub->save();
        return response()->json(['status' => 'canceled']);
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class AppleGooglePayController extends Controller
{
    public function pay(Request $request)
    {
        $user = auth()->user();
        $amount = $request->input('amount');
        $currency = $request->input('currency', config('payment.gateways.stripe.currency', 'SAR'));
        Stripe::setApiKey(config('payment.gateways.stripe.api_key'));
        $session = StripeSession::create([
            'payment_method_types' => ['card', 'apple_pay', 'google_pay'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => 'Apple/Google Pay Payment',
                    ],
                    'unit_amount' => intval($amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/payment/callback?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/payment/cancel'),
            'metadata' => [
                'user_id' => $user ? $user->id : null,
            ],
        ]);
        $transaction = PaymentTransaction::create([
            'gateway' => 'apple_google_pay',
            'transaction_id' => $session->id,
            'status' => 'pending',
            'amount' => $amount,
            'currency' => $currency,
            'user_id' => $user ? $user->id : null,
            'details' => [ 'session_url' => $session->url ],
        ]);
        return response()->json([
            'redirect' => $session->url,
            'transaction_id' => $transaction->id,
        ]);
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\LoyaltyPoint;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Notifications\PaymentStatusNotification;
use App\Notifications\SmsPaymentStatusNotification;
use Illuminate\Support\Facades\Notification;
use App\Services\InvoiceService;
use App\Services\CurrencyService;

class PaymentController extends Controller
{
    // نقطة بداية لعملية الدفع
    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'sometimes|string|max:8',
        ]);
        $gateway = config('payment.default');
        $amount = $request->input('amount');
        $currency = $request->input('currency', config("payment.gateways.$gateway.currency", 'SAR'));
        $user = $request->user();
        // دعم الدفع عند الاستلام
        if ($request->input('cod') == 1) {
            $transaction = PaymentTransaction::create([
                'gateway' => 'cod',
                'transaction_id' => uniqid('cod_'),
                'status' => 'pending',
                'amount' => $amount,
                'currency' => $currency,
                'display_currency' => $currency,
                'display_amount' => $amount,
                'conversion_rate' => 1,
                'user_id' => $user ? $user->id : null,
                'is_cod' => true,
                'details' => [],
            ]);
            Notification::route('mail', config('mail.admin_email'))->notify(new PaymentStatusNotification($transaction, 'pending'));
            Notification::route('nexmo', $user ? $user->phone : null)->notify(new SmsPaymentStatusNotification($transaction, 'pending'));
            return response()->json([
                'status' => 'pending',
                'message' => 'تم تسجيل طلب الدفع عند الاستلام',
                'transaction_id' => $transaction->id,
            ]);
        }
        if ($gateway === 'stripe') {
            Stripe::setApiKey(config('payment.gateways.stripe.api_key'));
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => [
                            'name' => 'Order Payment',
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
            $display_currency = $request->input('display_currency', $currency);
            $display_amount = $amount;
            $conversion_rate = 1;
            if ($display_currency !== $currency) {
                [$display_amount, $conversion_rate] = CurrencyService::convert($amount, $currency, $display_currency);
            }
            $transaction = PaymentTransaction::create([
                'gateway' => 'stripe',
                'transaction_id' => $session->id,
                'status' => 'pending',
                'amount' => $amount,
                'currency' => $currency,
                'display_currency' => $display_currency,
                'display_amount' => $display_amount,
                'conversion_rate' => $conversion_rate,
                'user_id' => $user ? $user->id : null,
                'details' => [ 'session_url' => $session->url ],
            ]);
            $invoicePath = InvoiceService::generatePDF($transaction);
            $transaction->details = array_merge($transaction->details ?? [], ['invoice_path' => $invoicePath]);
            $transaction->save();
            Notification::route('mail', config('mail.admin_email'))->notify(new PaymentStatusNotification($transaction, 'pending'));
            Notification::route('nexmo', $user ? $user->phone : null)->notify(new SmsPaymentStatusNotification($transaction, 'pending'));
            return response()->json([
                'redirect' => $session->url,
                'transaction_id' => $transaction->id,
            ]);
        }
        // دعم بوابات أخرى لاحقاً
        return response()->json(['status' => 'pending', 'message' => 'بوابة الدفع غير مفعلة أو غير مدعومة']);
    }

    // نتيجة الدفع (نجاح/فشل)
    public function callback(Request $request)
    {
        $sessionId = $request->input('session_id');
        if ($sessionId) {
            Stripe::setApiKey(config('payment.gateways.stripe.api_key'));
            $session = StripeSession::retrieve($sessionId);
            $transaction = PaymentTransaction::where('transaction_id', $sessionId)->first();
            if ($session && $transaction) {
                $transaction->status = $session->payment_status === 'paid' ? 'paid' : $session->payment_status;
                $transaction->details = array_merge($transaction->details ?? [], [ 'stripe_status' => $session->payment_status ]);
                $transaction->save();
                if ($transaction->status === 'paid') {
                    LoyaltyPoint::create([
                        'user_id' => $transaction->user_id,
                        'transaction_id' => $transaction->id,
                        'points' => intval($transaction->amount),
                        'type' => 'payment',
                    ]);
                }
                Notification::route('mail', config('mail.admin_email'))->notify(new PaymentStatusNotification($transaction, $transaction->status));
                Notification::route('nexmo', $transaction->user ? $transaction->user->phone : null)->notify(new SmsPaymentStatusNotification($transaction, $transaction->status));
                // يمكنك هنا تنفيذ منطق تفعيل الخدمة أو الطلب
                return response()->json(['status' => $transaction->status, 'message' => 'تمت معالجة الدفع بنجاح']);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'تعذر معالجة الدفع']);
    }
}

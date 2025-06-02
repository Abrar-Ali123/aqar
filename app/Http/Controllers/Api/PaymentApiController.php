<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Services\InvoiceService;

class PaymentApiController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        $amount = $request->input('amount');
        $currency = $request->input('currency', 'SAR');
        $gateway = $request->input('gateway', 'stripe');
        // ... بوابات أخرى يمكن إضافتها
        $transaction = PaymentTransaction::create([
            'gateway' => $gateway,
            'transaction_id' => uniqid('api_'),
            'status' => 'pending',
            'amount' => $amount,
            'currency' => $currency,
            'user_id' => $user ? $user->id : null,
            'details' => [],
        ]);
        $invoicePath = InvoiceService::generatePDF($transaction);
        $transaction->details = array_merge($transaction->details ?? [], ['invoice_path' => $invoicePath]);
        $transaction->save();
        return response()->json(['status' => 'created', 'transaction' => $transaction]);
    }
    public function show($id)
    {
        $transaction = PaymentTransaction::findOrFail($id);
        return response()->json(['transaction' => $transaction]);
    }
}

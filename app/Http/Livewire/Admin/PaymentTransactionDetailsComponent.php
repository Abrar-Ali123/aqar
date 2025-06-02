<?php
namespace App\Http\Livewire\Admin;

use App\Models\PaymentTransaction;
use Livewire\Component;

class PaymentTransactionDetailsComponent extends Component
{
    public $transaction;
    public $refund_status = null;

    public function mount($id)
    {
        $this->transaction = PaymentTransaction::findOrFail($id);
    }

    public function refund()
    {
        if ($this->transaction->gateway === 'stripe' && $this->transaction->status === 'paid') {
            $stripe = new \Stripe\StripeClient(config('payment.gateways.stripe.api_key'));
            $refund = $stripe->refunds->create([
                'payment_intent' => $this->transaction->transaction_id,
            ]);
            $this->refund_status = $refund->status;
            $this->transaction->status = 'refunded';
            $this->transaction->save();
        }
    }

    public function render()
    {
        return view('admin.payment-transaction-details', [
            'transaction' => $this->transaction,
            'refund_status' => $this->refund_status,
        ]);
    }
}

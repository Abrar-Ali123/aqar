<?php
namespace App\Http\Livewire\Admin;

use App\Models\PaymentTransaction;
use Livewire\Component;

class PaymentAnalyticsComponent extends Component
{
    public $total = 0;
    public $paid = 0;
    public $pending = 0;
    public $failed = 0;
    public $currency = 'SAR';
    public $byGateway = [];

    public function mount()
    {
        $this->currency = config('payment.gateways.' . config('payment.default') . '.currency', 'SAR');
        $this->total = PaymentTransaction::sum('amount');
        $this->paid = PaymentTransaction::where('status', 'paid')->sum('amount');
        $this->pending = PaymentTransaction::where('status', 'pending')->sum('amount');
        $this->failed = PaymentTransaction::where('status', 'failed')->sum('amount');
        $this->byGateway = PaymentTransaction::selectRaw('gateway, SUM(amount) as sum')->groupBy('gateway')->pluck('sum', 'gateway')->toArray();
    }

    public function render()
    {
        return view('admin.payment-analytics', [
            'total' => $this->total,
            'paid' => $this->paid,
            'pending' => $this->pending,
            'failed' => $this->failed,
            'currency' => $this->currency,
            'byGateway' => $this->byGateway,
        ]);
    }
}

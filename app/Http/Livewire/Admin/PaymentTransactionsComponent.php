<?php
namespace App\Http\Livewire\Admin;

use App\Models\PaymentTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentTransactionsComponent extends Component
{
    use WithPagination;
    public $status = '';
    public $gateway = '';

    public function render()
    {
        $query = PaymentTransaction::query();
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->gateway) {
            $query->where('gateway', $this->gateway);
        }
        $transactions = $query->latest()->paginate(20);
        return view('admin.payment-transactions', [
            'transactions' => $transactions
        ]);
    }
}

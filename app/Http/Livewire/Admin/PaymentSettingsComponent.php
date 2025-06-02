<?php
namespace App\Http\Livewire\Admin;

use Illuminate\Support\Arr;
use Livewire\Component;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class PaymentSettingsComponent extends Component
{
    public $gateway;
    public $stripe_api_key;
    public $stripe_publishable_key;
    public $currency;

    public function mount()
    {
        $this->gateway = config('payment.default');
        $this->stripe_api_key = config('payment.gateways.stripe.api_key');
        $this->stripe_publishable_key = config('payment.gateways.stripe.publishable_key');
        $this->currency = config('payment.gateways.stripe.currency', 'SAR');
    }

    public function save()
    {
        $env = file_get_contents(base_path('.env'));
        $env = preg_replace('/^PAYMENT_GATEWAY=.*/m', 'PAYMENT_GATEWAY=' . $this->gateway, $env);
        $env = preg_replace('/^STRIPE_SECRET_KEY=.*/m', 'STRIPE_SECRET_KEY=' . $this->stripe_api_key, $env);
        $env = preg_replace('/^STRIPE_PUBLISHABLE_KEY=.*/m', 'STRIPE_PUBLISHABLE_KEY=' . $this->stripe_publishable_key, $env);
        $env = preg_replace('/^PAYMENT_CURRENCY=.*/m', 'PAYMENT_CURRENCY=' . $this->currency, $env);
        file_put_contents(base_path('.env'), $env);
        Artisan::call('config:clear');
        session()->flash('success', 'تم تحديث إعدادات الدفع بنجاح');
    }

    public function render()
    {
        return view('admin.payment-settings');
    }
}

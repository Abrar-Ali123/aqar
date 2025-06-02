<?php
namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Arr;

class PaymentGatewaysComponent extends Component
{
    public $gateways = [];
    public $default;

    public function mount()
    {
        $this->gateways = config('payment.gateways');
        $this->default = config('payment.default');
    }

    public function toggle($key)
    {
        $env = file_get_contents(base_path('.env'));
        $enabled = Arr::get($this->gateways[$key], 'enabled', true);
        $this->gateways[$key]['enabled'] = !$enabled;
        $env = preg_replace('/^' . strtoupper($key) . '_PAYMENT_ENABLED=.*/m', strtoupper($key) . '_PAYMENT_ENABLED=' . ($this->gateways[$key]['enabled'] ? 'true' : 'false'), $env);
        file_put_contents(base_path('.env'), $env);
        session()->flash('success', 'تم تحديث حالة البوابة');
    }

    public function setDefault($key)
    {
        $env = file_get_contents(base_path('.env'));
        $env = preg_replace('/^PAYMENT_GATEWAY=.*/m', 'PAYMENT_GATEWAY=' . $key, $env);
        file_put_contents(base_path('.env'), $env);
        $this->default = $key;
        session()->flash('success', 'تم تعيين البوابة الافتراضية');
    }

    public function render()
    {
        return view('admin.payment-gateways', [
            'gateways' => $this->gateways,
            'default' => $this->default
        ]);
    }
}

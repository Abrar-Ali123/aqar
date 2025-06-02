<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AccountManagementComponent extends Component
{
    public $wizardStep = 0;
    public $countryCode = '966';
    public $phone_number;
    public $verificationCode;
    public $withConfirmed = false;
    public $withError = false;

    public function mount()
    {
        $this->wizardStep = 0; // تأكيد تعيين القيمة الابتدائية
    }

    protected $rules = [
        'phone_number' => 'required|numeric',
    ];

    public function render()
    {
        return view('livewire.account-management-component')
            ->layout('layouts.guest'); // استخدام قالب الضيوف
    }

    public function nextStep()
    {
        if ($this->wizardStep < 98) {
            $this->wizardStep++;
        }
    }

    public function previousStep()
    {
        if ($this->wizardStep > 0) {
            $this->wizardStep--;
        }
    }

    public function submit()
    {
        $this->validate();
        // هنا سيتم التعامل مع تسجيل الدخول
    }

    public function login()
    {
        try {
            $user = \App\Models\User::where('phone_number', $this->phone_number)->first();
            
            if ($user) {
                auth()->login($user);
                $this->withConfirmed = 'تم تسجيل الدخول بنجاح';
                $this->withError = false;
                return redirect()->intended('/dashboard');
            } else {
                $this->withError = 'رقم الهاتف غير مسجل';
                $this->withConfirmed = false;
                return;
            }
        } catch (\Exception $e) {
            $this->withError = 'حدث خطأ أثناء تسجيل الدخول: ' . $e->getMessage();
            $this->withConfirmed = false;
            return;
        }
    }

    public function loginOrRegister()
    {
        try {
            // التحقق من وجود المستخدم
            $user = \App\Models\User::where('phone_number', $this->phone_number)->first();

            if (!$user) {
                // إنشاء مستخدم جديد
                $user = \App\Models\User::create([
                    'phone_number' => $this->phone_number,
                    'password' => \Illuminate\Support\Facades\Hash::make(str_random(12)),
                    'is_active' => true,
                    'language_code' => app()->getLocale(),
                    'is_multilanguage_enabled' => true
                ]);

                // حفظ الأسماء بكل اللغات
                foreach ($this->translations as $locale => $name) {
                    $user->setTranslation('name', $locale, $name);
                }
                $user->save();

                // تسجيل دخول المستخدم
                auth()->login($user);

                $this->withConfirmed = 'تم التسجيل وتسجيل الدخول بنجاح';
                $this->withError = false;

                return redirect()->intended('/dashboard');
            } else {
                $this->withError = 'المستخدم مسجل مسبقاً';
                $this->withConfirmed = false;
                return;
            }
        } catch (\Exception $e) {
            $this->withError = 'حدث خطأ أثناء التسجيل: ' . $e->getMessage();
            $this->withConfirmed = false;
            return;
        }
    }
}

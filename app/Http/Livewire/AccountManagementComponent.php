<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountManagementComponent extends Component
{
    public $wizardStep = 0;
    public $countryCode = '966';
    public $phone_number;
    public $verificationCode;
    public $name;
    public $withConfirmed = false;
    public $withError = false;
    public $isNewUser = false;

    protected $rules = [
        'phone_number' => 'required|numeric|min:9|max:9',
        'verificationCode' => 'required|min:6|max:6',
        'name' => 'required_if:isNewUser,true|min:3|max:50'
    ];

    protected $messages = [
        'phone_number.required' => 'رقم الجوال مطلوب',
        'phone_number.numeric' => 'رقم الجوال يجب أن يكون أرقام فقط',
        'phone_number.min' => 'رقم الجوال يجب أن يكون 9 أرقام',
        'phone_number.max' => 'رقم الجوال يجب أن يكون 9 أرقام',
        'verificationCode.required' => 'رمز التحقق مطلوب',
        'verificationCode.min' => 'رمز التحقق يجب أن يكون 6 أرقام',
        'verificationCode.max' => 'رمز التحقق يجب أن يكون 6 أرقام',
        'name.required_if' => 'الاسم مطلوب',
        'name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
        'name.max' => 'الاسم يجب ألا يتجاوز 50 حرف'
    ];

    protected $listeners = [
        'codeSent' => 'handleCodeSent',
        'codeError' => 'handleCodeError',
        'verificationCompleted' => 'handleVerificationCompleted'
    ];

    public $isLoading = false;
    public $lastAttemptTime = 0;
    public $retryDelay = 60; // ثواني

    public function mount()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard', ['locale' => app()->getLocale()]);
        }
        $this->wizardStep = 0;
    }

    public function render()
    {
        return view('livewire.account-management-component')
            ->layout('layouts.guest');
    }

    public function handleCodeSent()
    {
        $this->isLoading = false;
        $this->lastAttemptTime = time();
        
        // التحقق من وجود المستخدم
        $phone = $this->countryCode . $this->phone_number;
        $user = User::where('phone', $phone)->first();
        
        if ($user) {
            // مستخدم موجود -> انتقل للتحقق
            $this->isNewUser = false;
            $this->wizardStep = 1;
        } else {
            // مستخدم جديد -> انتقل للتسجيل
            $this->isNewUser = true;
            $this->wizardStep = 2;
        }
        
        $this->withConfirmed = 'تم إرسال رمز التحقق بنجاح';
        $this->withError = false;
        $this->emit('codeWasSent');
    }

    public function handleCodeError($message)
    {
        $this->isLoading = false;
        $this->withError = $message;
        $this->withConfirmed = false;
        $this->emit('errorOccurred', ['message' => $message]);
    }

    public function handleVerificationCompleted($phoneNumber)
    {
        $this->isLoading = false;
        $this->withConfirmed = 'تم التحقق بنجاح';
        $this->withError = false;
        $this->phone_number = $phoneNumber;
        $this->loginOrRegister();
    }

    public function canAttemptVerification()
    {
        if (time() - $this->lastAttemptTime < $this->retryDelay) {
            $remainingTime = $this->retryDelay - (time() - $this->lastAttemptTime);
            $this->withError = "يرجى الانتظار {$remainingTime} ثانية قبل المحاولة مرة أخرى";
            return false;
        }
        return true;
    }

    public function nextStep()
    {
        if ($this->wizardStep < 2) {
            $this->wizardStep++;
        }
    }

    public function previousStep()
    {
        if ($this->wizardStep > 0) {
            $this->wizardStep--;
        }
    }

    public function loginOrRegister()
    {
        $this->validate();

        try {
            // التحقق من رمز التحقق
            if (empty($this->verificationCode)) {
                $this->withError = 'الرجاء إدخال رمز التحقق';
                return;
            }

            $phone = $this->countryCode . $this->phone_number;
            
            if ($this->isNewUser) {
                // مستخدم جديد -> إنشاء حساب
                if (empty($this->name)) {
                    $this->withError = 'الرجاء إدخال الاسم';
                    return;
                }

                $user = User::create([
                    'name' => $this->name,
                    'phone' => $phone,
                    'password' => Hash::make(Str::random(12))
                ]);
            } else {
                // مستخدم موجود -> تسجيل دخول
                $user = User::where('phone', $phone)->first();
                if (!$user) {
                    $this->withError = 'حدث خطأ غير متوقع';
                    return;
                }
            }

            // تسجيل الدخول
            auth()->login($user);
            session()->flash('message', 'تم تسجيل الدخول بنجاح');
            return redirect()->route('dashboard', ['locale' => app()->getLocale()]);

        } catch (\Exception $e) {
            $this->withError = 'حدث خطأ أثناء التسجيل';
            $this->withConfirmed = false;
            return;
        }
    }
}
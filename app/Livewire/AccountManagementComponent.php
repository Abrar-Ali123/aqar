<?php

namespace App\Livewire;

use Livewire\Component;

class AccountManagementComponent extends Component
{
    public $step = 'phone';
    public $mobile = '';
    public $name = '';
    public $message = '';
    public $verificationCode = '';

    protected $rules = [
        'mobile' => 'required|regex:/^(05)[0-9]{8}$/',
        'name' => 'required|min:2',
        'verificationCode' => 'required|size:6'
    ];

    protected $messages = [
        'mobile.required' => 'رقم الجوال مطلوب',
        'mobile.regex' => 'رقم الجوال غير صحيح',
        'verificationCode.required' => 'رمز التحقق مطلوب',
        'verificationCode.size' => 'رمز التحقق يجب أن يكون 6 أرقام',
        'name.required' => 'الاسم مطلوب',
        'name.min' => 'الاسم يجب أن يكون حرفين على الأقل'
    ];

    public function mount()
    {
        $this->step = 'phone';
    }

    public function sendCode()
    {
        try {
            $this->validate([
                'mobile' => 'required|regex:/^(05)[0-9]{8}$/'
            ]);

            $this->message = 'تم إرسال رمز التحقق بنجاح';
            $this->step = 'verify';
        } catch (\Exception $e) {
            $this->message = 'خطأ: ' . $e->getMessage();
        }
    }

    public function verifyCode()
    {
        try {
            $this->validate([
                'verificationCode' => 'required|size:6'
            ]);

            if ($this->verificationCode === '123456') {
                $this->message = 'تم التحقق من الرمز بنجاح';
                $this->step = 'name';
            } else {
                $this->message = 'خطأ: رمز التحقق غير صحيح';
            }
        } catch (\Exception $e) {
            $this->message = 'خطأ: ' . $e->getMessage();
        }
    }

    public function register()
    {
        try {
            $this->validate([
                'name' => 'required|min:2'
            ]);

            $this->message = 'تم التسجيل بنجاح';
            $this->step = 'done';
        } catch (\Exception $e) {
            $this->message = 'خطأ: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.account-management-component');
    }
}
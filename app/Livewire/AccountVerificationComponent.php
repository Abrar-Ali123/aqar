<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AccountVerificationComponent extends Component
{
    public $countryCode = '966';
    public $phone_number;
    public $verificationCode;
    public $showNameForm = false;
    public $translations = [];
    public $firebase_uid;

    protected $listeners = ['showNameForm'];

    protected $rules = [
        'phone_number' => 'required|numeric',
        'verificationCode' => 'nullable',
        'translations' => 'nullable|array',
        'firebase_uid' => 'required'
    ];

    public function render()
    {
        return view('livewire.account-verification-component')
            ->layout('layouts.guest');
    }

    public function submitVerification()
    {
        $this->validate([
            'phone_number' => 'required|numeric',
        ]);
    }

    public function showNameForm()
    {
        $this->showNameForm = true;
    }

    public function loginOrRegister()
    {
        Log::info('Translations:', $this->translations);
        Log::info('Firebase UID:', [$this->firebase_uid]);

        // التحقق من وجود اسم واحد على الأقل
        if (empty($this->translations) || !array_filter(array_map(fn($t) => $t['name'] ?? '', $this->translations))) {
            session()->flash('error', 'يجب إدخال الاسم بلغة واحدة على الأقل');
            return;
        }

        $this->validate([
            'translations.*.name' => 'nullable|string|max:255',
            'firebase_uid' => 'required'
        ]);

        $fullPhone = '+' . $this->countryCode . $this->phone_number;

        // التحقق من عدم وجود UID مسبقاً
        $existingUser = User::where('firebase_uid', $this->firebase_uid)->first();

        if ($existingUser) {
            Auth::login($existingUser);
            return redirect()->to('/' . app()->getLocale() . '/dashboard');
        }

        try {
            // تسجيل المستخدم لأول مرة
            $translations = [];
            foreach ($this->translations as $locale => $data) {
                if (!empty($data['name'])) {
                    $translations[$locale] = ['name' => $data['name']];
                }
            }

            $user = User::create([
                'phone_number' => $fullPhone,
                'firebase_uid' => $this->firebase_uid,
                'is_active' => true,
            ]);

            foreach ($translations as $locale => $data) {
                $user->translateOrNew($locale)->name = $data['name'];
            }
            $user->save();

            Auth::login($user);
            return redirect()->to('/' . app()->getLocale() . '/dashboard');
        } catch (\Exception $e) {
            Log::error('Error creating user:', [$e->getMessage()]);
            session()->flash('error', 'حدث خطأ أثناء التسجيل: ' . $e->getMessage());
            return;
        }
    }
}

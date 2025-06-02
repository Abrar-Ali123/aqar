<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountManagementComponent extends Component
{
    protected $firebaseAuth;

    use WithFileUploads;

    public $withConfirmed = false;

    public $countryCode;

    public $selectedRoleId;

    public $primaryRoles;

    public $email;

    public $password;

    public $phone_number;

    public $avatar;

    public $bank_account;

    public $role_id;

    public $facility_id;

    public $bank_id;

    public $latitude;

    public $longitude;

    public $google_maps_url;

    public $primary_role;

    public $facebook;

    public $twitter;

    public $instagram;

    public $linkedin;

    public $snapchat;

    public $tiktok;

    public $pinterest;

    public $youtube;

    public $whatsapp_number;

    public $telegram;

    public $names = [];

    public $addresses = [];

    public $passwordConfirmation;

    public $resetEmail;

    public $resetToken;

    public $verificationCode;

    public $isVerified = false;

    public $step = 0;

    public $withError = false;

    public $registrationType;

    public $translations = [];

    protected $listeners = ['codeConfirm', 'werrorCode'];

    protected function rules()
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];

        foreach ($this->locales as $locale => $label) {
            $rules["names.$locale"] = 'required|string|max:255';
        }

        return $rules;
    }

    public function render()
    {
        return view('livewire.account-management-component')
            ->layout('components.layouts.blank');
    }

    public function codeConfirm($code)
    {
        $this->withConfirmed = $code;
        $this->isVerified = true;
    }

    public function werrorCode($code)
    {
        $this->withError = $code;
        $this->isVerified = false;
    }

    public function login(Request $request)
    {
        $idToken = $request->input('idToken');
        $phoneNumber = $request->input('phone_number');

        $phone_number = "+{$request->countryCode}{$phoneNumber}";
        $user = User::where('phone_number', $phone_number)->first();

        if ($user) {
            Auth::login($user);
            return response()->json(['status' => 'done']);
        } else {
            Auth::logout();
            return response()->json(['status' => 'register', 'redirect' => route('register')]);
        }
    }

    public function loginOrRegister(Request $request)
    {
        $idToken = $request->input('idToken');
        $phoneNumber = $request->input('phone_number');

        $phone_number = "+{$request->countryCode}{$phoneNumber}";
        $user = User::where('phone_number', $phone_number)->first();

        if ($user) {
            Auth::login($user);

            return response()->json(['status' => 'done']);
        } else {
            $user = User::create([
                'phone_number' => $phone_number,
                'primary_role' => 'باحث عن عقار',

            ]);

            foreach ($request->names as $locale => $name) {
                $user->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['name' => $name]
                );
            }

            Auth::login($user);

            return response()->json([
                'status' => 'registered',
                'message' => 'تم تسجيلك بنجاح. يمكنك الآن إكمال ملفك الشخصي.',
            ]);
        }
    }
}
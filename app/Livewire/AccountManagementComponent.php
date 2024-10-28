<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountManagementComponent extends Component
{
    // protected $listeners = ['verificationSuccess' => 'loginOrRegister'];
    protected $firebaseAuth;

    use WithFileUploads;

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

    public $withConfirmed = false;

    public $withError = false;

    protected $listeners = ['codeConfirm', 'werrorCode'];

    public function mount()
    {
        $this->roles = Role::where('is_primary', true)->get();

        $this->firebaseAuth = (new Factory)->withServiceAccount(config('firebase.credentials_path'))->createAuth();

    }

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

    public function loginOrRegister(Request $request)
    {
        // فرض تحقق الرمز هنا إذا كنت تحتاج ذلك
        // التحقق من الرمز الوارد من Firebase
        // if ($request->verificationCode !== 'التحقق من الرمز هنا') {
        //     return response()->json(['status' => 'error', 'message' => 'رمز التحقق غير صحيح.']);
        // }

        $phone_number = "+{$request->countryCode}{$request->phone_number}";
        $user = User::where('phone_number', $phone_number)->first();

        if ($user) {
            // إذا كان المستخدم موجودًا بالفعل، قم بتسجيل دخوله
            Auth::login($user);

            return response()->json(['status' => 'done']);
        } else {
            // إذا لم يكن المستخدم موجودًا، توجيهه لعملية التسجيل
            return response()->json(['status' => 'register']);
        }
    }

    public function login()
    {
        $phone_number = "+$this->countryCode$this->phone_number";

        $user = User::where('phone_number', $phone_number)->first();
        if ($user) {

            // إذا كان المستخدم مسجل بالفعل، قم بتسجيل دخوله
            Auth::login($user);
            session()->flash('message', 'You are logged in successfully.');

            return response()->json('done');
        } else {
            return response()->json(false);

            //             إذا لم يكن المستخدم مسجل بالفعل، قم بعملية التسجيل
            $user = User::create([
                'name' => null,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone_number' => $phone_number,
                'bank_account' => $this->bank_account,
                'role_id' => $this->role_id,
                'facility_id' => $this->facility_id,
                'bank_id' => $this->bank_id,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'google_maps_url' => $this->google_maps_url,
                'primary_role' => $this->primary_role,
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'instagram' => $this->instagram,
                'linkedin' => $this->linkedin,
                'snapchat' => $this->snapchat,
                'tiktok' => $this->tiktok,
                'pinterest' => $this->pinterest,
                'youtube' => $this->youtube,
                'whatsapp' => $this->whatsapp_number,
                'telegram' => $this->telegram,
                'role_id' => $this->selectedRoleId,
            ]);

            if ($this->avatar) {
                $user->avatar = $this->avatar->store('avatars', 'public');
            }

            foreach ($this->names as $locale => $name) {
                UserTranslation::create([
                    'user_id' => $user->id,
                    'locale' => $locale,
                    'name' => $name,
                    'info' => $this->addresses[$locale] ?? '',
                ]);
            }
            // تحقق مما إذا كان الدور مدفوعًا وتوجيه لصفحة الدفع
            $selectedRole = Role::find($this->selectedRoleId);
            if ($selectedRole && $selectedRole->is_paid) {
                session()->put('user_id', $user->id); // حفظ معرف المستخدم في الجلسة لاستخدامه بعد الدفع

                return redirect()->to('/payment'); // توجيه المستخدم إلى صفحة الدفع
            }

            // إكمال التسجيل للأدوار المجانية
            Auth::login($user);
            session()->flash('success', 'User registered successfully.');

            return response()->json(false);

            return redirect()->to('/dashboard1');
        }
    }
}

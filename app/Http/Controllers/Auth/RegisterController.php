<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Facility;
use App\Models\FacilityTranslation;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/admin';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'type' => ['required', 'in:user,organization,employee,bank_employee'],
            'organization_id' => ['nullable', 'exists:facilities,id'],
            'logo' => ['nullable'],
            'license' => ['nullable', 'string'],
            'facility_name' => ['nullable', 'string'],
            'bank_id' => ['nullable', 'exists:banks,id'],
        ]);

        if ($validator->fails()) {
            dd($validator->errors());
        }

        return $validator;
    }

    protected function create(array $data)
    {

        if ($data['type'] == 'user') {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone'],
                'password' => Hash::make($data['password']),
                'type' => $data['type'],
            ]);
        }


        if ($data['type'] == 'organization') {

            $facility = Facility::create([
                'license' => $data['license'],
                'logo' => $data['logo']->store('logos'),
            ]);

            FacilityTranslation::create([
                'facility_id' => $facility->id,
                'name' => $data['facility_name'],
                'locale' => 'en',
            ]);


            $manager = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone'],
                'password' => Hash::make($data['password']),
                'type' => 'manager',
                'facility_id' => $facility->id,
            ]);

            return $manager;
        }

        if ($data['type'] == 'employee') {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone'],
                'password' => Hash::make($data['password']),
                'type' => $data['type'],
                'facility_id' => $data['organization_id'],
            ]);
        }

        if ($data['type'] == 'bank_employee') {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone'],
                'password' => Hash::make($data['password']),
                'type' => $data['type'],
                'bank_id' => $data['bank_id'],
            ]);
        }
    }
}

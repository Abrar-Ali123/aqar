<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityTranslation;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleTranslation;
use App\Models\User;
use App\Models\UserTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function home()
    {
        $facilities = Facility::all();

        return view('home', compact('facilities'));
    }

    public function index()
    {
        $facilities = Facility::all();
        $locale = app()->getLocale();

        return view('facilities.index', compact('facilities', 'locale'));
    }

    public function create()
    {
        $permissions = Permission::with('translations')->get();
        $roles = Role::with('translations')->get();

        return view('facilities.create', [
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    private function getAllRouteNames($facility_id)
    {
        return collect(Route::getRoutes())->map(function ($route) use ($facility_id) {
            return $route->getName().'?facility_id='.$facility_id;
        })->filter()->values()->toArray();
    }

    public function store(Request $request)
    {

        $facility = new Facility;
        $facility->is_active = $request->has('is_active');
        $facility->License = $request->License;
        $facility->latitude = $request->latitude;
        $facility->longitude = $request->longitude;
        $facility->google_maps_url = $request->google_maps_url;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $facility->logo = $path;
        }

        if ($request->hasFile('header')) {
            $path = $request->file('header')->store('headers', 'public');
            $facility->header = $path;
        }

        $facility->save();

        foreach ($request->input('translations') as $locale => $translationData) {
            FacilityTranslation::create([
                'facility_id' => $facility->id,
                'locale' => $locale,
                'name' => $translationData['name'],
                'info' => $translationData['info'] ?? '',
            ]);
        }

        $admin = new User;
        $admin->email = $request->email;
        $admin->phone_number = $request->phone_number;
        $admin->password = Hash::make($request->password);
        $admin->save();

        foreach ($request->input('user_translations') as $locale => $translationData) {
            UserTranslation::create([
                'user_id' => $admin->id,
                'locale' => $locale,
                'name' => $translationData['name'],
                'info' => $translationData['info'] ?? '',
            ]);
        }

        Auth::login($admin);

        $managerPermissions = [
            'facilities.index',
            'facilities.edit',
            'facilities.destroy',
            'products.index',
            'products.create',
            'products.edit',
            'products.destroy',
            'roles.index',
            'permissions.index',
            'attributes.index',
            'categories.index',
        ];

        $permission = Permission::firstOrCreate(
            ['pages' => json_encode($managerPermissions)],
            ['pages' => json_encode($managerPermissions)]
        );

        $adminRole = Role::query()->whereHas('roleTranslation', function ($q) {
            $q->where('name', 'Facility Manager');
            $q->where('is_primary', '1');
        })->first();

        if ($adminRole == null) {
            $adminRole = Role::create(
                [
                    'is_primary' => 1,
                ]
            );

            $ee = RoleTranslation::create([
                'role_id' => $adminRole->id,
                'locale' => 'ar',
                'name' => 'مدير منشأة',
                'description' => 'صلاحية كاملة لإدارة المنشأة',
            ]);

            RoleTranslation::create([
                'role_id' => $adminRole->id,
                'locale' => 'en',
                'name' => 'Facility Manager',
                'description' => 'Full permission to manage the facility',
            ]);

        }

        $adminRole->permissions()->attach($permission->id);

        $admin->facilityRoles()->attach($facility->id, [
            'role_id' => $adminRole->id,
            'facility_id' => $facility->id,
        ]);

        return redirect('/')->with('success', 'تم إنشاء المنشأة والمستخدم الإداري بنجاح.');
    }

    public function edit($id)
    {
        $facility = Facility::with('translations')->findOrFail($id);

        return view('facilities.edit', compact('facility'));
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $facility->is_active = $request->has('is_active');
        $facility->License = $request->input('License');
        $facility->latitude = $request->input('latitude');
        $facility->longitude = $request->input('longitude');
        $facility->google_maps_url = $request->input('google_maps_url');

        if ($request->hasFile('logo')) {
            $oldLogo = $facility->logo;
            $facility->logo = $request->file('logo')->store('logos', 'public');
            if ($oldLogo) {
                Storage::delete($oldLogo);
            }
        }

        if ($request->hasFile('header')) {
            $oldHeader = $facility->header;
            $facility->header = $request->file('header')->store('headers', 'public');
            if ($oldHeader) {
                Storage::delete($oldHeader);
            }
        }

        $facility->save();

        foreach ($request->input('translations', []) as $locale => $translationData) {
            $translation = $facility->translations()->where('locale', $locale)->firstOrCreate([]);
            $translation->name = $translationData['name'];
            $translation->info = $translationData['info'] ?? '';
            $translation->save();
        }

        return redirect('/facilities/index')->with('success', 'تم تحديث المنشأة بنجاح.');
    }

    public function show($id)
    {
        $facility = Facility::with('products')->findOrFail($id);

        return view('facilities.show', [
            'facility' => $facility,
            'products' => $facility->products,
        ]);
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        if ($facility->logo) {
            Storage::delete('public/'.$facility->logo);
        }
        $facility->delete();

        return redirect()->route('facilities.index')->with('success', 'تم حذف المنشأة بنجاح.');
    }
}

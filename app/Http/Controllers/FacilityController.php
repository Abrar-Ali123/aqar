<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityTranslation;
use App\Models\Permission;
use App\Models\PermissionTranslation;
use App\Models\Role;
use App\Models\RoleTranslation;
use App\Models\User;
use App\Models\UserTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
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
        $admin->facility_id = $facility->id;
        $admin->save();

        foreach ($request->input('user_translations') as $locale => $translationData) {
            UserTranslation::create([
                'user_id' => $admin->id,
                'locale' => $locale,
                'name' => $translationData['name'],
                'info' => $translationData['info'] ?? '',
            ]);
        }

        $adminRole = Role::firstOrCreate(
            ['facility_id' => $facility->id],
            ['description' => 'صلاحية كاملة لإدارة المنشأة']
        );

        RoleTranslation::create([
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

        $allRoutesWithFacilityId = $this->getAllRouteNames($facility->id);

        $permissions = Permission::create([
            'name' => 'إدارة كاملة لمنشأة '.$facility->id,
            'pages' => json_encode($allRoutesWithFacilityId),
            'facility_id' => $facility->id,
        ]);

        $locales = ['ar', 'en'];
        $translations = [
            'ar' => ['name' => 'إدارة المنشأة', 'description' => 'الصلاحية لإدارة المنشأة بشكل كامل'],
            'en' => ['name' => 'Facility Management', 'description' => 'Full permission to manage the facility'],
        ];

        foreach ($locales as $locale) {
            $translationExists = PermissionTranslation::where('permission_id', $permissions->id)
                ->where('locale', $locale)
                ->exists();

            if (! $translationExists) {
                PermissionTranslation::create([
                    'permission_id' => $permissions->id,
                    'locale' => $locale,
                    'name' => $translations[$locale]['name'],
                    'description' => $translations[$locale]['description'],
                ]);
            }
        }

        $adminRole->permissions()->attach($permissions->id);

        $admin->roles()->attach($adminRole->id);

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
        $facility = Facility::with('translations')->findOrFail($id);

        return view('facilities.show', compact('facility'));
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

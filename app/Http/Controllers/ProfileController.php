<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends TranslatableController
{
    protected $translatableFields = [
        'bio' => ['nullable', 'string'],
        'address' => ['nullable', 'string'],
        'company' => ['nullable', 'string'],
        'position' => ['nullable', 'string'],
    ];

    /**
     * عرض نموذج تحرير الملف الشخصي للمستخدم
     */
    public function edit(Request $request): View
    {
        $user = Auth::user()->load('roles', 'media');
        $languages = $this->getLanguages();
        $translations = $this->prepareTranslations($user, array_keys($this->translatableFields));
        return view('profile.edit', compact('user', 'languages', 'translations'));
    }

    /**
     * تحديث معلومات الملف الشخصي للمستخدم
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect()->back()
                        ->with('error', __('messages.current_password_incorrect'))
                        ->withInput();
                }
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $this->handleTranslations($user, $request, array_keys($this->translatableFields));

            if ($request->hasFile('avatar')) {
                $user->addMedia($request->file('avatar'))
                    ->toMediaCollection('avatars');
            }

            return Redirect::route('profile.edit')
                ->with('success', __('messages.profile_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.profile_update_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * حذف حساب المستخدم
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

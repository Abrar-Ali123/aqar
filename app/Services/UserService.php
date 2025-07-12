<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Create a new user and handle all related data.
     *
     * @param array $validatedData
     * @return User
     */
    public function createUser(array $validatedData): User
    {
        return DB::transaction(function () use ($validatedData) {
            $user = User::create([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'phone' => $validatedData['phone'] ?? null,
                'password' => Hash::make($validatedData['password']),
                'is_active' => $validatedData['is_active'] ?? false,
                'status' => $validatedData['status'] ?? 'active',
            ]);

            $this->handleAvatar($user, $validatedData['avatar'] ?? null);
            $this->syncRelations($user, $validatedData);

            return $user;
        });
    }

    /**
     * Update an existing user and handle all related data.
     *
     * @param User $user
     * @param array $validatedData
     * @return User
     */
    public function updateUser(User $user, array $validatedData): User
    {
        return DB::transaction(function () use ($user, $validatedData) {
            $userData = [
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'phone' => $validatedData['phone'] ?? null,
                'is_active' => $validatedData['is_active'] ?? false,
                'status' => $validatedData['status'] ?? 'active',
            ];

            // Update password only if provided
            if (!empty($validatedData['password'])) {
                $userData['password'] = Hash::make($validatedData['password']);
            }

            $user->update($userData);

            $this->handleAvatar($user, $validatedData['avatar'] ?? null);
            $this->syncRelations($user, $validatedData);

            return $user;
        });
    }

    /**
     * Delete a user and their avatar.
     *
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->delete();
        });
    }

    /**
     * Handle user avatar upload.
     *
     * @param User $user
     * @param mixed $avatarFile
     */
    private function handleAvatar(User $user, $avatarFile): void
    {
        if (!$avatarFile) {
            return;
        }

        // Delete old avatar if it exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $avatarFile->store('users/avatars', 'public');
        $user->update(['avatar' => $path]);
    }

    /**
     * Sync user relations like translations, roles, and settings.
     *
     * @param User $user
     * @param array $data
     */
    private function syncRelations(User $user, array $data): void
    {
        // Sync translations
        foreach ($data['first_name'] as $locale => $firstName) {
            $user->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'first_name' => $firstName,
                    'last_name' => $data['last_name'][$locale] ?? null,
                    'bio' => $data['bio'][$locale] ?? null,
                    'address' => $data['address'][$locale] ?? null,
                ]
            );
        }

        // Sync roles
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        // Sync settings
        if (isset($data['settings'])) {
            $user->settings()->updateOrCreate([], $data['settings']);
        }
    }
}

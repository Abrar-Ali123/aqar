<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SavedSearch;
use Illuminate\Auth\Access\HandlesAuthorization;

class SavedSearchPolicy
{
    use HandlesAuthorization;

    public function update(User $user, SavedSearch $savedSearch)
    {
        return $user->id === $savedSearch->user_id;
    }

    public function delete(User $user, SavedSearch $savedSearch)
    {
        return $user->id === $savedSearch->user_id;
    }
}

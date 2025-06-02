<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Facility;

class FacilityPolicy
{
    public function view(User $user, Facility $facility)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_facility');
    }

    public function update(User $user, Facility $facility)
    {
        return $user->hasPermission('update_facility');
    }

    public function delete(User $user, Facility $facility)
    {
        return $user->hasPermission('delete_facility');
    }
}

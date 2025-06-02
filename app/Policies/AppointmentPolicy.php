<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->user_id || 
               $user->can('manage_appointments');
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->user_id || 
               $user->can('manage_appointments');
    }

    public function delete(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->user_id || 
               $user->can('manage_appointments');
    }

    public function updateStatus(User $user, Appointment $appointment)
    {
        return $user->can('manage_appointments');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentComponent extends Component
{
    public $type;
    public $appointment_time;
    public $description;
    public $appointments;

    protected $rules = [
        'type' => 'required|in:attendance,leave,training,interview,evaluation',
        'appointment_time' => 'required|date|after:now',
        'description' => 'required|string|min:10',
    ];

    public function mount()
    {
        $this->appointments = Appointment::where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function createAppointment()
    {
        $this->validate();

        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'type' => $this->type,
            'appointment_time' => $this->appointment_time,
            'description' => $this->description,
            'status' => 'scheduled'
        ]);

        $this->reset(['type', 'appointment_time', 'description']);
        $this->appointments = Appointment::where('user_id', auth()->id())
            ->latest()
            ->get();

        session()->flash('message', __('Appointment created successfully'));
    }

    public function updateStatus($appointmentId, $status)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        if ($appointment->user_id !== auth()->id()) {
            session()->flash('error', __('Unauthorized action'));
            return;
        }

        $appointment->update(['status' => $status]);
        
        $this->appointments = Appointment::where('user_id', auth()->id())
            ->latest()
            ->get();

        session()->flash('message', __('Appointment status updated successfully'));
    }

    public function render()
    {
        return view('livewire.appointment-component');
    }
}

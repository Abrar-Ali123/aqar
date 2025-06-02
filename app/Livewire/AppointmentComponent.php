<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentComponent extends Component
{
    use WithPagination;

    public $facility_id;
    public $type;
    public $appointment_time;
    public $description;
    public $status = 'scheduled';
    public $metadata = [];
    
    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function rules()
    {
        return [
            'facility_id' => 'required|exists:facilities,id',
            'type' => 'required|in:attendance,leave,training,interview,evaluation',
            'appointment_time' => 'required|date|after:now',
            'description' => 'required|string',
            'status' => 'required|in:scheduled,approved,rejected,completed',
            'metadata' => 'array'
        ];
    }

    public function createAppointment()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'facility_id' => $this->facility_id,
            'type' => $this->type,
            'appointment_time' => $this->appointment_time,
            'status' => $this->status,
            'metadata' => $this->metadata
        ];

        $translations = [
            app()->getLocale() => [
                'description' => $this->description
            ]
        ];

        $this->appointmentService->create($data, $translations);

        $this->reset(['type', 'appointment_time', 'description', 'metadata']);
        $this->dispatch('appointment-created');
    }

    public function updateStatus($appointmentId, $newStatus)
    {
        $this->appointmentService->updateStatus($appointmentId, $newStatus);
        $this->dispatch('appointment-updated');
    }

    public function render()
    {
        $appointments = $this->appointmentService->getFacilityAppointments(
            $this->facility_id,
            $this->type
        );

        return view('livewire.appointment-component', [
            'appointments' => $appointments
        ]);
    }
}

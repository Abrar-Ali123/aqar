<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\AppointmentService;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index()
    {
        $appointments = $this->appointmentService->getUserAppointments(auth()->id());
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('appointments.create');
    }

    public function store(AppointmentRequest $request)
    {
        $result = $this->appointmentService->createAppointment($request->validated());

        if ($result['success']) {
            return redirect()->route('appointments.index')
                ->with('success', __('appointments.messages.created'));
        }

        return back()->withErrors(['error' => $result['message']]);
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        return view('appointments.edit', compact('appointment'));
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $result = $this->appointmentService->updateAppointment($appointment, $request->validated());

        if ($result['success']) {
            return redirect()->route('appointments.show', $appointment)
                ->with('success', __('appointments.messages.updated'));
        }

        return back()->withErrors(['error' => $result['message']]);
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        
        $this->appointmentService->deleteAppointment($appointment);
        return redirect()->route('appointments.index')
            ->with('success', __('appointments.messages.deleted'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $this->authorize('updateStatus', $appointment);
        
        $result = $this->appointmentService->updateAppointmentStatus(
            $appointment,
            $request->input('status')
        );

        if ($result['success']) {
            return redirect()->route('appointments.show', $appointment)
                ->with('success', __('appointments.messages.status_updated'));
        }

        return back()->withErrors(['error' => $result['message']]);
    }
}

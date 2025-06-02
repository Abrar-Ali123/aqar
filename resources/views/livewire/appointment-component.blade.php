<div>
    {{-- نموذج إنشاء موعد جديد --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Create New Appointment') }}</h3>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="createAppointment">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">{{ __('Type') }}</label>
                            <select wire:model="type" class="form-control">
                                <option value="">{{ __('Select Type') }}</option>
                                <option value="attendance">{{ __('Attendance') }}</option>
                                <option value="leave">{{ __('Leave') }}</option>
                                <option value="training">{{ __('Training') }}</option>
                                <option value="interview">{{ __('Interview') }}</option>
                                <option value="evaluation">{{ __('Evaluation') }}</option>
                            </select>
                            @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="appointment_time">{{ __('Time') }}</label>
                            <input type="datetime-local" wire:model="appointment_time" class="form-control">
                            @error('appointment_time') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea wire:model="description" class="form-control" rows="3"></textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- قائمة المواعيد --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">{{ __('Appointments') }}</h3>
            <div class="card-tools">
                <select wire:model="type" class="form-control">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="attendance">{{ __('Attendance') }}</option>
                    <option value="leave">{{ __('Leave') }}</option>
                    <option value="training">{{ __('Training') }}</option>
                    <option value="interview">{{ __('Interview') }}</option>
                    <option value="evaluation">{{ __('Evaluation') }}</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Time') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->type_name }}</td>
                                <td>{{ $appointment->appointment_time->format('Y-m-d H:i') }}</td>
                                <td>{{ $appointment->translate()->description }}</td>
                                <td>
                                    <span class="badge badge-{{ $appointment->status === 'approved' ? 'success' : ($appointment->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ __($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($appointment->status === 'scheduled')
                                        <button wire:click="updateStatus({{ $appointment->id }}, 'approved')" class="btn btn-sm btn-success">
                                            {{ __('Approve') }}
                                        </button>
                                        <button wire:click="updateStatus({{ $appointment->id }}, 'rejected')" class="btn btn-sm btn-danger">
                                            {{ __('Reject') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No appointments found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- إشعارات النجاح والفشل --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        @if (session()->has('message'))
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">{{ __('Notification') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('message') }}
                </div>
            </div>
        @endif
    </div>
</div>

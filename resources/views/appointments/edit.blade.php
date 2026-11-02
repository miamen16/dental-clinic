@extends('layouts.clinic')

@section('title', 'Edit Appointment')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Appointment</h1>
            <span class="text-muted">
                {{ $appointment->patient?->full_name }} · {{ $appointment->start_at->format('d M Y, H:i') }}
            </span>
        </div>
        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary">View Appointment</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
        </div>
    @endif

    <form action="{{ route('appointments.update', $appointment) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Who --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">Patient & Doctor</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                        <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id"
                            name="patient_id" required>
                            <option value="">-- Select patient --</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}" @selected((string) old('patient_id', $appointment->patient_id) === (string) $patient->id)>
                                    {{ $patient->full_name }} (#{{ $patient->patient_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="dentist_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                        <select class="form-select @error('dentist_id') is-invalid @enderror" id="dentist_id"
                            name="dentist_id" required>
                            <option value="">-- Select doctor --</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" @selected((string) old('dentist_id', $appointment->dentist_id) === (string) $doctor->id)>
                                    Dr. {{ $doctor->name }}{{ $doctor->specialty ? ' — ' . $doctor->specialty : '' }}{{ $doctor->is_active ? '' : ' (inactive)' }}
                                </option>
                            @endforeach
                        </select>
                        @error('dentist_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                        <select class="form-select @error('service_id') is-invalid @enderror" id="service_id"
                            name="service_id" required>
                            <option value="">-- Select service --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected((string) old('service_id', $appointment->service_id) === (string) $service->id)>
                                    {{ $service->name }} — {{ number_format($service->price, 2) }}{{ $service->is_active ? '' : ' (inactive)' }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- When --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">Date, Time & Status</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                            name="date" value="{{ old('date', $appointment->start_at->format('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="time" class="form-label">Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('time') is-invalid @enderror" id="time"
                            name="time" value="{{ old('time', $appointment->start_at->format('H:i')) }}" step="900"
                            required>
                        @error('time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $appointment->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-text mt-2">Appointments are booked for 1 hour.</div>
            </div>
        </div>

        {{-- Details --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="reason" class="form-label">Reason for Visit</label>
                        <input type="text" class="form-control @error('reason') is-invalid @enderror" id="reason"
                            name="reason" value="{{ old('reason', $appointment->reason) }}" maxlength="255"
                            placeholder="e.g. Toothache on lower left side">
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                            rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection

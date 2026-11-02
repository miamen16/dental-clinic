@extends('layouts.clinic')

@section('title', 'Book Appointment')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Book Appointment</h1>
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Back to Appointments</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
        </div>
    @endif

    @if ($patients->isEmpty() || $doctors->isEmpty() || $services->isEmpty())
        <div class="alert alert-warning">
            <strong>You can't book an appointment yet.</strong>
            <ul class="mb-0 mt-1">
                @if ($patients->isEmpty())
                    <li>No patients — <a href="{{ route('patients.create') }}">add a patient</a>.</li>
                @endif
                @if ($doctors->isEmpty())
                    <li>No active doctors — <a href="{{ route('doctors.create') }}">add a doctor</a>.</li>
                @endif
                @if ($services->isEmpty())
                    <li>No active services — add at least one service first.</li>
                @endif
            </ul>
        </div>
    @endif

    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

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
                                <option value="{{ $patient->id }}" @selected((string) old('patient_id', request('patient_id')) === (string) $patient->id)>
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
                                <option value="{{ $doctor->id }}" @selected((string) old('dentist_id') === (string) $doctor->id)>
                                    Dr. {{ $doctor->name }}{{ $doctor->specialty ? ' — ' . $doctor->specialty : '' }}
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
                                <option value="{{ $service->id }}" @selected((string) old('service_id') === (string) $service->id)>
                                    {{ $service->name }} — {{ number_format($service->price, 2) }}
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
            <div class="card-header fw-semibold">Date & Time</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                            name="date" value="{{ old('date') }}" min="{{ now()->toDateString() }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="time" class="form-label">Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('time') is-invalid @enderror" id="time"
                            name="time" value="{{ old('time') }}" step="900" required>
                        @error('time')
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
                            name="reason" value="{{ old('reason') }}" maxlength="255"
                            placeholder="e.g. Toothache on lower left side">
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                            rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Book Appointment</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection

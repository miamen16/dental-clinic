@extends('layouts.clinic')

@section('title', 'Appointment')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                Appointment
                <span class="badge bg-{{ $appointment->statusColor() }} fs-6 align-middle ms-1">{{ $appointment->statusLabel() }}</span>
            </h1>
            <span class="text-muted">
                {{ $appointment->start_at->format('l, d M Y') }} ·
                {{ $appointment->start_at->format('H:i') }} – {{ $appointment->end_at->format('H:i') }}
            </span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Back to Appointments</a>
            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST"
                onsubmit="return confirm('Delete this appointment?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Patient --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-semibold">Patient</div>
                <div class="card-body">
                    @if ($appointment->patient)
                        <h5 class="mb-1">
                            <a href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->full_name }}</a>
                        </h5>
                        <div class="text-muted mb-3">#{{ $appointment->patient->patient_number }}</div>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Phone</dt>
                            <dd class="col-sm-8">{{ $appointment->patient->phone_number ?: '—' }}</dd>
                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">{{ $appointment->patient->email }}</dd>
                            <dt class="col-sm-4">Allergies</dt>
                            <dd class="col-sm-8 mb-0 {{ $appointment->patient->allergies ? 'text-danger' : 'text-muted' }}">
                                {{ $appointment->patient->allergies ?: 'None recorded' }}
                            </dd>
                        </dl>
                    @else
                        <span class="text-muted">Patient not found.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Doctor & service --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-semibold">Doctor & Service</div>
                <div class="card-body">
                    @if ($appointment->dentist)
                        <h5 class="mb-1">
                            <a href="{{ route('doctors.show', $appointment->dentist) }}">Dr. {{ $appointment->dentist->name }}</a>
                        </h5>
                        <div class="text-muted mb-3">{{ $appointment->dentist->specialty ?: 'No specialization' }}</div>
                    @endif
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Service</dt>
                        <dd class="col-sm-8">
                            @if ($appointment->service)
                                <a href="{{ route('services.show', $appointment->service) }}">{{ $appointment->service->name }}</a>
                            @else
                                —
                            @endif
                        </dd>
                        <dt class="col-sm-4">Price</dt>
                        <dd class="col-sm-8 mb-0">
                            {{ $appointment->service ? number_format($appointment->service->price, 2) : '—' }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Details --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">Details</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Date</dt>
                <dd class="col-sm-9">{{ $appointment->start_at->format('l, d M Y') }}</dd>

                <dt class="col-sm-3">Time</dt>
                <dd class="col-sm-9">
                    {{ $appointment->start_at->format('H:i') }} – {{ $appointment->end_at->format('H:i') }}
                    <span class="text-muted">({{ $appointment->start_at->diffInMinutes($appointment->end_at) }} min)</span>
                </dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-{{ $appointment->statusColor() }}">{{ $appointment->statusLabel() }}</span>
                </dd>

                <dt class="col-sm-3">Reason for Visit</dt>
                <dd class="col-sm-9">{{ $appointment->reason ?: '—' }}</dd>

                <dt class="col-sm-3">Notes</dt>
                <dd class="col-sm-9 mb-0">
                    @if ($appointment->notes)
                        {!! nl2br(e($appointment->notes)) !!}
                    @else
                        <span class="text-muted">No notes</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    {{-- Medical record --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Medical Record</span>
            @if ($appointment->medicalRecord)
                <a href="{{ route('medical-records.show', $appointment->medicalRecord) }}"
                    class="btn btn-sm btn-outline-primary">View Record</a>
            @else
                <a href="{{ route('medical-records.create', ['appointment_id' => $appointment->id]) }}"
                    class="btn btn-sm btn-outline-primary">+ Add Record</a>
            @endif
        </div>
        <div class="card-body">
            @if ($appointment->medicalRecord)
                {!! nl2br(e($appointment->medicalRecord->diagnosis)) !!}
            @else
                <span class="text-muted">No medical record for this appointment yet.</span>
            @endif
        </div>
    </div>

    <p class="text-muted small">
        Booked {{ $appointment->created_at?->format('d M Y, H:i') }}
        @if ($appointment->creator)
            by {{ $appointment->creator->name }}
        @endif
        · Last updated {{ $appointment->updated_at?->format('d M Y, H:i') }}
    </p>
@endsection

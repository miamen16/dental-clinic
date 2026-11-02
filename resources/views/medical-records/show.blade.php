@extends('layouts.clinic')

@section('title', 'Medical Record')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Medical Record</h1>
            <span class="text-muted">
                #{{ $record->id }} · {{ $record->created_at->format('l, d M Y') }}
            </span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">Back to Medical Records</a>
            <a href="{{ route('medical-records.edit', $record) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('medical-records.destroy', $record) }}" method="POST"
                onsubmit="return confirm('Delete this medical record?');">
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
                    @if ($record->patient)
                        <h5 class="mb-1">
                            <a href="{{ route('patients.show', $record->patient) }}">{{ $record->patient->full_name }}</a>
                        </h5>
                        <div class="text-muted mb-3">#{{ $record->patient->patient_number }}</div>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Date of Birth</dt>
                            <dd class="col-sm-8">{{ $record->patient->date_of_birth?->format('d M Y') ?? '—' }}</dd>
                            <dt class="col-sm-4">Blood Type</dt>
                            <dd class="col-sm-8">{{ $record->patient->blood_type ?: '—' }}</dd>
                            <dt class="col-sm-4">Allergies</dt>
                            <dd class="col-sm-8 mb-0 {{ $record->patient->allergies ? 'text-danger' : 'text-muted' }}">
                                {{ $record->patient->allergies ?: 'None recorded' }}
                            </dd>
                        </dl>
                    @else
                        <span class="text-muted">Patient not found.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Doctor & appointment --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-semibold">Doctor & Appointment</div>
                <div class="card-body">
                    @if ($record->dentist)
                        <h5 class="mb-1">
                            <a href="{{ route('doctors.show', $record->dentist) }}">Dr. {{ $record->dentist->name }}</a>
                        </h5>
                        <div class="text-muted mb-3">{{ $record->dentist->specialty ?: 'No specialization' }}</div>
                    @endif
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Appointment</dt>
                        <dd class="col-sm-8">
                            @if ($record->appointment)
                                <a href="{{ route('appointments.show', $record->appointment) }}">
                                    {{ $record->appointment->start_at->format('d M Y, H:i') }}
                                </a>
                            @else
                                —
                            @endif
                        </dd>
                        <dt class="col-sm-4">Service</dt>
                        <dd class="col-sm-8 mb-0">{{ $record->appointment?->service?->name ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Clinical details --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">Clinical Details</div>
        <div class="card-body">
            <dl class="row mb-0">
                @foreach ([
                    'diagnosis' => 'Diagnosis',
                    'clinical_notes' => 'Clinical Notes',
                    'recommendations' => 'Recommendations',
                ] as $field => $label)
                    <dt class="col-sm-3">{{ $label }}</dt>
                    <dd class="col-sm-9 {{ $loop->last ? 'mb-0' : '' }}">
                        @if ($record->$field)
                            {!! nl2br(e($record->$field)) !!}
                        @else
                            <span class="text-muted">None recorded</span>
                        @endif
                    </dd>
                @endforeach
            </dl>
        </div>
    </div>

    <p class="text-muted small">
        Created {{ $record->created_at?->format('d M Y, H:i') }}
        · Last updated {{ $record->updated_at?->format('d M Y, H:i') }}
    </p>
@endsection

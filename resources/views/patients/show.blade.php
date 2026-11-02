@extends('layouts.clinic')

@section('title', $patient->full_name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $patient->full_name }}</h1>
            <span class="text-muted">Patient #{{ $patient->patient_number }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Back to Patients</a>
            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('patients.destroy', $patient) }}" method="POST"
                onsubmit="return confirm('Delete this patient?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    {{-- Personal information --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">Personal Information</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Patient Number</dt>
                <dd class="col-sm-9">{{ $patient->patient_number }}</dd>

                <dt class="col-sm-3">First Name</dt>
                <dd class="col-sm-9">{{ $patient->first_name }}</dd>

                <dt class="col-sm-3">Last Name</dt>
                <dd class="col-sm-9">{{ $patient->last_name }}</dd>

                <dt class="col-sm-3">Date of Birth</dt>
                <dd class="col-sm-9">
                    @if ($patient->date_of_birth)
                        {{ $patient->date_of_birth->format('d M Y') }}
                        <span class="text-muted">({{ $patient->date_of_birth->age }} years)</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </dd>

                <dt class="col-sm-3">Gender</dt>
                <dd class="col-sm-9">{{ $patient->gender ? ucfirst($patient->gender) : '—' }}</dd>

                <dt class="col-sm-3">Blood Type</dt>
                <dd class="col-sm-9 mb-0">{{ $patient->blood_type ?: '—' }}</dd>
            </dl>
        </div>
    </div>

    {{-- Contact information --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">Contact Information</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><a href="mailto:{{ $patient->email }}">{{ $patient->email }}</a></dd>

                <dt class="col-sm-3">Phone Number</dt>
                <dd class="col-sm-9">
                    @if ($patient->phone_number)
                        <a href="tel:{{ $patient->phone_number }}">{{ $patient->phone_number }}</a>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9 mb-0">{{ $patient->address ?: '—' }}</dd>
            </dl>
        </div>
    </div>

    {{-- Medical information --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">Medical Information</div>
        <div class="card-body">
            <div class="row g-4">
                @foreach ([
            'medical_history' => 'Medical History',
            'allergies' => 'Allergies',
            'current_medications' => 'Current Medications',
            'notes' => 'Notes',
        ] as $field => $label)
                    <div class="col-md-6">
                        <h6 class="fw-semibold">{{ $label }}</h6>
                        @if ($patient->$field)
                            <p class="mb-0 {{ $field === 'allergies' ? 'text-danger' : '' }}">{!! nl2br(e($patient->$field)) !!}</p>
                        @else
                            <p class="mb-0 text-muted">None recorded</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Medical records --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Medical Records</span>
            <a href="{{ route('medical-records.create', ['patient_id' => $patient->id]) }}"
                class="btn btn-sm btn-outline-primary">+ Add Record</a>
        </div>
        @if ($patient->medicalRecords->isEmpty())
            <div class="card-body text-muted">No medical records yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Diagnosis</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patient->medicalRecords as $record)
                            <tr>
                                <td class="text-nowrap">{{ $record->created_at->format('d M Y') }}</td>
                                <td>{{ $record->dentist ? 'Dr. ' . $record->dentist->name : '—' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($record->diagnosis, 80) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('medical-records.show', $record) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Record information --}}
    <p class="text-muted small">
        Created {{ $patient->created_at?->format('d M Y, H:i') }}
        · Last updated {{ $patient->updated_at?->format('d M Y, H:i') }}
    </p>
@endsection

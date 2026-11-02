@extends('layouts.clinic')

@section('title', 'Edit ' . $patient->full_name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Patient</h1>
            <span class="text-muted">{{ $patient->full_name }} · #{{ $patient->patient_number }}</span>
        </div>
        <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary">View Patient</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
        </div>
    @endif

    <form action="{{ route('patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Personal information --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">Personal Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="patient_number" class="form-label">Patient Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('patient_number') is-invalid @enderror"
                               id="patient_number" name="patient_number"
                               value="{{ old('patient_number', $patient->patient_number) }}" required>
                        @error('patient_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                               id="first_name" name="first_name"
                               value="{{ old('first_name', $patient->first_name) }}" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                               id="last_name" name="last_name"
                               value="{{ old('last_name', $patient->last_name) }}" required>
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                               id="date_of_birth" name="date_of_birth"
                               value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}"
                               max="{{ now()->toDateString() }}">
                        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="">-- Select --</option>
                            @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('gender', $patient->gender) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="blood_type" class="form-label">Blood Type</label>
                        <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type">
                            <option value="">-- Select --</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                <option value="{{ $type }}" @selected(old('blood_type', $patient->blood_type) === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact information --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">Contact Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email"
                               value="{{ old('email', $patient->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                               id="phone_number" name="phone_number"
                               value="{{ old('phone_number', $patient->phone_number) }}">
                        @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                               id="address" name="address"
                               value="{{ old('address', $patient->address) }}">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Medical information --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">Medical Information</div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ([
                        'medical_history' => 'Medical History',
                        'allergies' => 'Allergies',
                        'current_medications' => 'Current Medications',
                        'notes' => 'Notes',
                    ] as $field => $label)
                        <div class="col-md-6">
                            <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                            <textarea class="form-control @error($field) is-invalid @enderror"
                                      id="{{ $field }}" name="{{ $field }}" rows="3">{{ old($field, $patient->$field) }}</textarea>
                            @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection

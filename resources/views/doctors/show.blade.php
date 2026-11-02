@extends('layouts.clinic')

@section('title', $doctor->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Dr. {{ $doctor->name }}</h1>
            <span class="text-muted">{{ $doctor->specialty ?: 'No specialization' }}</span>
            @if ($doctor->is_active)
                <span class="badge bg-success ms-2">Active</span>
            @else
                <span class="badge bg-secondary ms-2">Inactive</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">Back to Doctors</a>
            <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('doctors.destroy', $doctor) }}" method="POST"
                onsubmit="return confirm('Delete this doctor?');">
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
                <dt class="col-sm-3">First Name</dt>
                <dd class="col-sm-9">{{ $doctor->first_name ?: '—' }}</dd>

                <dt class="col-sm-3">Last Name</dt>
                <dd class="col-sm-9">{{ $doctor->last_name ?: '—' }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><a href="mailto:{{ $doctor->email }}">{{ $doctor->email }}</a></dd>

                <dt class="col-sm-3">Contact Number</dt>
                <dd class="col-sm-9">
                    @if ($doctor->phone)
                        <a href="tel:{{ $doctor->phone }}">{{ $doctor->phone }}</a>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Date of Birth</dt>
                <dd class="col-sm-9">
                    @if ($doctor->date_of_birth)
                        {{ $doctor->date_of_birth->format('d M Y') }}
                        <span class="text-muted">({{ $doctor->date_of_birth->age }} years)</span>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Gender</dt>
                <dd class="col-sm-9 mb-0">{{ $doctor->gender ? ucfirst($doctor->gender) : '—' }}</dd>
            </dl>
        </div>
    </div>

    {{-- Professional information --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">Professional Information</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Specialization</dt>
                <dd class="col-sm-9">{{ $doctor->specialty ?: '—' }}</dd>

                <dt class="col-sm-3">Experience</dt>
                <dd class="col-sm-9">
                    {{ $doctor->experience !== null ? $doctor->experience . ' ' . Str::plural('year', $doctor->experience) : '—' }}
                </dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    <form action="{{ route('doctors.toggle-status', $doctor) }}" method="POST" class="mb-0">
                        @csrf
                        @method('PATCH')
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="status"
                                @checked($doctor->is_active) onchange="this.form.submit()">
                            <label class="form-check-label {{ $doctor->is_active ? 'text-success' : 'text-muted' }}"
                                for="status">
                                {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                            </label>
                        </div>
                    </form>
                </dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9 mb-0">
                    @if ($doctor->bio)
                        {!! nl2br(e($doctor->bio)) !!}
                    @else
                        <span class="text-muted">No description</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    {{-- Address information --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">Address Information</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">{{ $doctor->address ?: '—' }}</dd>

                <dt class="col-sm-3">City</dt>
                <dd class="col-sm-9">{{ $doctor->city ?: '—' }}</dd>

                <dt class="col-sm-3">Country</dt>
                <dd class="col-sm-9">{{ $doctor->country ?: '—' }}</dd>

                <dt class="col-sm-3">Postal Code</dt>
                <dd class="col-sm-9 mb-0">{{ $doctor->postal_code ?: '—' }}</dd>
            </dl>
        </div>
    </div>

    <p class="text-muted small">
        Added {{ $doctor->created_at?->format('d M Y, H:i') }}
        · Last updated {{ $doctor->updated_at?->format('d M Y, H:i') }}
    </p>
@endsection

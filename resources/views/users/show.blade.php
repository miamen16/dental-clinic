@extends('layouts.clinic')

@section('title', $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $user->name }}</h1>
            <span class="text-muted">{{ $user->specialty ?: 'No specialization' }}</span>
            @if ($user->is_active)
                <span class="badge bg-success ms-2">Active</span>
            @else
                <span class="badge bg-secondary ms-2">Inactive</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back to Users</a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('users.destroy', $user) }}" method="POST"
                onsubmit="return confirm('Delete this user?');">
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
                <dd class="col-sm-9">{{ $user->first_name ?: '—' }}</dd>

                <dt class="col-sm-3">Last Name</dt>
                <dd class="col-sm-9">{{ $user->last_name ?: '—' }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></dd>

                <dt class="col-sm-3">Contact Number</dt>
                <dd class="col-sm-9">
                    @if ($user->phone)
                        <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Date of Birth</dt>
                <dd class="col-sm-9">
                    @if ($user->date_of_birth)
                        {{ $user->date_of_birth->format('d M Y') }}
                        <span class="text-muted">({{ $user->date_of_birth->age }} years)</span>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Gender</dt>
                <dd class="col-sm-9 mb-0">{{ $user->gender ? ucfirst($user->gender) : '—' }}</dd>
            </dl>
        </div>
    </div>

    {{-- Professional information --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">Professional Information</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Specialization</dt>
                <dd class="col-sm-9">{{ $user->specialty ?: '—' }}</dd>

                <dt class="col-sm-3">Experience</dt>
                <dd class="col-sm-9">
                    {{ $user->experience !== null ? $user->experience . ' ' . Str::plural('year', $user->experience) : '—' }}
                </dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="mb-0">
                        @csrf
                        @method('PATCH')
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="status"
                                @checked($user->is_active) onchange="this.form.submit()">
                            <label class="form-check-label {{ $user->is_active ? 'text-success' : 'text-muted' }}"
                                for="status">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </label>
                        </div>
                    </form>
                </dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9 mb-0">
                    @if ($user->bio)
                        {!! nl2br(e($user->bio)) !!}
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
                <dd class="col-sm-9">{{ $user->address ?: '—' }}</dd>

                <dt class="col-sm-3">City</dt>
                <dd class="col-sm-9">{{ $user->city ?: '—' }}</dd>

                <dt class="col-sm-3">Country</dt>
                <dd class="col-sm-9">{{ $user->country ?: '—' }}</dd>

                <dt class="col-sm-3">Postal Code</dt>
                <dd class="col-sm-9 mb-0">{{ $user->postal_code ?: '—' }}</dd>
            </dl>
        </div>
    </div>

    <p class="text-muted small">
        Added {{ $user->created_at?->format('d M Y, H:i') }}
        · Last updated {{ $user->updated_at?->format('d M Y, H:i') }}
    </p>
@endsection

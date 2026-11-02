@extends('layouts.clinic')

@section('title', $receptionist->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $receptionist->name }}</h1>
            <span class="text-muted">Receptionist</span>
            @if ($receptionist->is_active)
                <span class="badge bg-success ms-2">Active</span>
            @else
                <span class="badge bg-secondary ms-2">Inactive</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('receptionists.index') }}" class="btn btn-outline-secondary">Back to Receptionists</a>
            <a href="{{ route('receptionists.edit', $receptionist) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('receptionists.destroy', $receptionist) }}" method="POST"
                onsubmit="return confirm('Delete this receptionist?');">
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
                <dd class="col-sm-9">{{ $receptionist->first_name ?: '—' }}</dd>

                <dt class="col-sm-3">Last Name</dt>
                <dd class="col-sm-9">{{ $receptionist->last_name ?: '—' }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><a href="mailto:{{ $receptionist->email }}">{{ $receptionist->email }}</a></dd>

                <dt class="col-sm-3">Contact Number</dt>
                <dd class="col-sm-9">
                    @if ($receptionist->phone)
                        <a href="tel:{{ $receptionist->phone }}">{{ $receptionist->phone }}</a>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Date of Birth</dt>
                <dd class="col-sm-9">
                    @if ($receptionist->date_of_birth)
                        {{ $receptionist->date_of_birth->format('d M Y') }}
                        <span class="text-muted">({{ $receptionist->date_of_birth->age }} years)</span>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Gender</dt>
                <dd class="col-sm-9">{{ $receptionist->gender ? ucfirst($receptionist->gender) : '—' }}</dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9 mb-0">
                    @if ($receptionist->bio)
                        {!! nl2br(e($receptionist->bio)) !!}
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
                <dd class="col-sm-9">{{ $receptionist->address ?: '—' }}</dd>

                <dt class="col-sm-3">City</dt>
                <dd class="col-sm-9">{{ $receptionist->city ?: '—' }}</dd>

                <dt class="col-sm-3">Country</dt>
                <dd class="col-sm-9">{{ $receptionist->country ?: '—' }}</dd>

                <dt class="col-sm-3">Postal Code</dt>
                <dd class="col-sm-9 mb-0">{{ $receptionist->postal_code ?: '—' }}</dd>
            </dl>
        </div>
    </div>

    <p class="text-muted small">
        Added {{ $receptionist->created_at?->format('d M Y, H:i') }}
        · Last updated {{ $receptionist->updated_at?->format('d M Y, H:i') }}
    </p>
@endsection

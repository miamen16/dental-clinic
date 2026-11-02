@extends('layouts.clinic')

@section('title', $service->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Service Name: {{ $service->name }}</h1>
            @if ($service->code)
                <span class="text-muted">Service Code: {{ $service->code }}</span>
            @endif
            @if ($service->is_active)
                <span class="badge bg-success ms-2">Active</span>
            @else
                <span class="badge bg-secondary ms-2">Inactive</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Back to Services</a>
            <a href="{{ route('services.edit', $service) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('services.destroy', $service) }}" method="POST"
                onsubmit="return confirm('Delete this service?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Price</div>
                    <div class="display-6 fw-semibold">{{ number_format($service->price, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Appointments Booked</div>
                    <div class="display-6 fw-semibold">{{ $service->appointments_count }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-semibold">Service Information</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9">{{ $service->name }}</dd>

                <dt class="col-sm-3">Code</dt>
                <dd class="col-sm-9">{{ $service->code ?: '—' }}</dd>
                <dt class="col-sm-3">Tax</dt>
                <dd class="col-sm-9">
                    @if ($service->tax)
                        <div class="fw-semibold">{{ $service->tax->name }}</div>
                        <div class="small text-muted">{{ $service->tax->rate }}%</div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    <form action="{{ route('services.toggle-status', $service) }}" method="POST" class="mb-0">
                        @csrf
                        @method('PATCH')
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="status"
                                @checked($service->is_active) onchange="this.form.submit()">
                            <label class="form-check-label {{ $service->is_active ? 'text-success' : 'text-muted' }}"
                                for="status">
                                {{ $service->is_active ? 'Active — can be booked' : 'Inactive — hidden from booking' }}
                            </label>
                        </div>
                    </form>
                </dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9 mb-0">
                    @if ($service->description)
                        {!! nl2br(e($service->description)) !!}
                    @else
                        <span class="text-muted">No description</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    <p class="text-muted small">
        Added {{ $service->created_at?->format('d M Y, H:i') }}
        · Last updated {{ $service->updated_at?->format('d M Y, H:i') }}
    </p>
@endsection

@extends('layouts.clinic')

@section('title', 'Services')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Services</h1>
        <a href="{{ route('services.create') }}" class="btn btn-primary">+ Add Service</a>
    </div>
    {{-- filtering --}}
    @php($filtering = request()->hasAny(['search', 'code', 'status']))
    <form method="GET" action="{{ route('services.index') }}" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="Search services..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label for="code" class="form-label small mb-1">Code</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Search by code..."
                    value="{{ request('code') }}">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label small mb-1">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill">Filter</button>
                @if ($filtering)
                    <a href="{{ route('services.index') }}" class="btn btn-link px-1">Clear</a>
                @endif
            </div>
        </div>
    </form>
    <div class="card">
        @if ($services->isEmpty())
            <div class="card-body text-center text-muted py-5">
                <p class="mb-3">No services yet. Add services so they can be booked in appointments.</p>
                <a href="{{ route('services.create') }}" class="btn btn-primary">Add your first service</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Appointments</th>
                            <th class="text-center">Tax</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td class="text-muted">{{ $service->code ?: '—' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $service->name }}</div>
                                    @if ($service->description)
                                        <div class="small text-muted">{{ Str::limit($service->description, 70) }}</div>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($service->price, 2) }}</td>
                                <td class="text-center">{{ $service->appointments_count }}</td>
                                <td class="text-center">
                                    @if ($service->tax)
                                        <div class="fw-semibold">{{ $service->tax->name }}</div>
                                        <div class="small text-muted">{{ $service->tax->rate }}%</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('services.toggle-status', $service) }}" method="POST"
                                        class="mb-0">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="status-{{ $service->id }}" @checked($service->is_active)
                                                onchange="this.form.submit()">
                                            <label
                                                class="form-check-label small {{ $service->is_active ? 'text-success' : 'text-muted' }}"
                                                for="status-{{ $service->id }}">
                                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('services.show', $service) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('services.edit', $service) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('services.destroy', $service) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this service?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@extends('layouts.clinic')

@section('title', 'Doctors')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Doctors</h1>
        <a href="{{ route('doctors.create') }}" class="btn btn-primary">+ Add Doctor</a>
    </div>

    {{-- Filters --}}
    @php($filtering = request()->hasAny(['search', 'specialty', 'status', 'gender']))
    <form method="GET" action="{{ route('doctors.index') }}" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="search" class="form-control" id="search" name="search" value="{{ request('search') }}"
                    placeholder="ID, Name, email or phone">
            </div>

            <div class="col-md-3">
                <label for="specialty" class="form-label small mb-1">Specialization</label>
                <select class="form-select" id="specialty" name="specialty">
                    <option value="">All specializations</option>
                    @foreach (\App\Models\User::SPECIALIZATIONS as $specialization)
                        <option value="{{ $specialization }}" @selected(request('specialty') === $specialization)>{{ $specialization }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="status" class="form-label small mb-1">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="gender" class="form-label small mb-1">Gender</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="">All</option>
                    @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('gender') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill">Filter</button>
                @if ($filtering)
                    <a href="{{ route('doctors.index') }}" class="btn btn-link px-1">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card">
        @if ($doctors->isEmpty())
            <div class="card-body text-center text-muted py-5">
                @if ($filtering)
                    <p class="mb-0">No doctors match these filters.</p>
                @else
                    <p class="mb-3">No doctors yet.</p>
                    <a href="{{ route('doctors.create') }}" class="btn btn-primary">Add the first doctor</a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->id }}</td>
                                <td>
                                    <div class="fw-semibold">Dr. {{ $doctor->name }}</div>
                                    @if ($doctor->experience !== null)
                                        <div class="small text-muted">{{ $doctor->experience }}
                                            {{ Str::plural('year', $doctor->experience) }} experience</div>
                                    @endif
                                </td>
                                <td>{{ $doctor->specialty ?: '—' }}</td>
                                <td>{{ $doctor->email }}</td>
                                <td>{{ $doctor->phone ?: '—' }}</td>
                                <td>{{ $doctor->gender ? ucfirst($doctor->gender) : '—' }}</td>
                                <td>
                                    <form action="{{ route('doctors.toggle-status', $doctor->id) }}" method="POST"
                                        class="mb-0">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="status-{{ $doctor->id }}" @checked($doctor->is_active)
                                                onchange="this.form.submit()">
                                            <label
                                                class="form-check-label small {{ $doctor->is_active ? 'text-success' : 'text-muted' }}"
                                                for="status-{{ $doctor->id }}">
                                                {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('doctors.show', $doctor) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('doctors.edit', $doctor) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this doctor?');">
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

    <div class="mt-3">
        {{ $doctors->links() }}
    </div>
@endsection

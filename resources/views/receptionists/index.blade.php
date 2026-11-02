@extends('layouts.clinic')

@section('title', 'Receptionists')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Receptionists</h1>
        <a href="{{ route('receptionists.create') }}" class="btn btn-primary">+ Add Receptionist</a>
    </div>
    {{-- Filtering --}}
    @php($filtering = request()->hasAny(['search', 'status']))
    <form method="GET" action="{{ route('receptionists.index') }}" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="search" class="form-control" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Name, email or phone">
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
                    <a href="{{ route('receptionists.index') }}" class="btn btn-link px-1">Clear</a>
                @endif
            </div>
        </div>
    </form>
    <div class="card">
        @if ($receptionists->isEmpty())
            <div class="card-body text-center text-muted py-5">
                <p class="mb-3">No receptionists yet.</p>
                <a href="{{ route('receptionists.create') }}" class="btn btn-primary">Add the first receptionist</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receptionists as $receptionist)
                            <tr>
                                <td>{{ $receptionist->id }}</td>
                                <td class="fw-semibold">{{ $receptionist->name }}</td>
                                <td>{{ $receptionist->email }}</td>
                                <td>{{ $receptionist->phone ?: '—' }}</td>
                                <td>{{ $receptionist->gender ? ucfirst($receptionist->gender) : '—' }}</td>
                                <td>
                                    <span class="{{ $receptionist->is_active ? 'text-success' : 'text-muted' }}">
                                        {{ $receptionist->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('receptionists.show', $receptionist) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('receptionists.edit', $receptionist) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('receptionists.destroy', $receptionist) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this receptionist?');">
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
        {{ $receptionists->links() }}
    </div>
@endsection

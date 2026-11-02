@extends('layouts.clinic')
@section('title', 'Users')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Users</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add User</a>
    </div>
    @php($filtering = request()->hasAny(['search', 'role', 'status']))
    <form method="GET" action="{{ route('users.index') }}" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="Search users..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="role" class="form-label small mb-1">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">All Roles</option>
                    @foreach (\App\Models\User::ROLES as $role)
                        <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
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
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill">Filter</button>
                @if ($filtering)
                    <a href="{{ route('users.index') }}" class="btn btn-link px-1">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="text-end">
                                <!-- Actions like edit, delete can be added here -->
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="{{ route('users.show', $user->id) }}"
                                    class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-sm btn-outline-danger"
                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">Delete</a>
                                <form id="delete-form-{{ $user->id }}"
                                    action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($users->isEmpty())
            <div class="card-body text-center text-muted py-5">
                <p class="mb-3">No users found. Add users to manage the system.</p>
                <a href="{{ route('users.create') }}" class="btn btn-primary">Add your first user</a>
            </div>
        @endif
    </div>
@endsection

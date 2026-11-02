@extends('layouts.clinic')
@section('title', 'Patients')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Patients</h1>
        <a href="{{ route('patients.create') }}" class="btn btn-primary">+ Add Patient</a>
    </div>
    {{-- Filters --}}
    @php($filtering = request()->hasAny(['search', 'gender', 'blood_type']))
    <form method="GET" action="{{ route('patients.index') }}" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="search" class="form-control" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Name, patient #, email or phone">
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
                <label for="blood_type" class="form-label small mb-1">Blood Type</label>
                <select class="form-select" id="blood_type" name="blood_type">
                    <option value="">All</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                        <option value="{{ $type }}" @selected(request('blood_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill">Filter</button>
                @if ($filtering)
                    <a href="{{ route('patients.index') }}" class="btn btn-link px-1">Clear</a>
                @endif
            </div>
        </div>
    </form>
    <div class="card">
        @if ($patients->isEmpty())
            <div class="card-body text-center">
                <p class="mb-0">No patients found.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Patient Number</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registration Date</th>
                            <th>Gender</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr>
                                <td>{{ $patient->id }}</td>
                                <td>{{ $patient->patient_number }}</td>
                                <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone_number }}</td>
                                <td>{{ $patient->created_at->format('d M Y') }}</td>
                                <td>{{ $patient->gender }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('patients.show', $patient) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('patients.edit', $patient) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this patient?');">
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

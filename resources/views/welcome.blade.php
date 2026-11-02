@extends('layouts.clinic')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard</h1>
            <span class="text-muted">Welcome back, {{ auth()->user()->name }}</span>
        </div>
        <a href="{{ route('patients.create') }}" class="btn btn-primary">+ Add Patient</a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Patients</div>
                    <div class="display-6 fw-semibold">{{ $totalPatients }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">New This Month</div>
                    <div class="display-6 fw-semibold">{{ $newThisMonth }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent patients --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Recent Patients</span>
            <a href="{{ route('patients.index') }}" class="small">View all</a>
        </div>

        @if ($recentPatients->isEmpty())
            <div class="card-body text-center text-muted py-5">
                <p class="mb-3">No patients yet.</p>
                <a href="{{ route('patients.create') }}" class="btn btn-primary">Add your first patient</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Patient #</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Added</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentPatients as $patient)
                            <tr>
                                <td>{{ $patient->patient_number }}</td>
                                <td>{{ $patient->full_name }}</td>
                                <td>{{ $patient->phone_number ?: '—' }}</td>
                                <td>{{ $patient->created_at->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('patients.show', $patient) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

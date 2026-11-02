@extends('layouts.clinic')

@section('title', 'Medical Records')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Medical Records</h1>
        <a href="{{ route('medical-records.create') }}" class="btn btn-primary">+ Add Medical Record</a>
    </div>

    @php($filtering = request()->anyFilled(['search', 'dentist_id']))

    {{-- Filters --}}
    <form method="GET" action="{{ route('medical-records.index') }}" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Patient name, patient # or diagnosis">
            </div>

            <div class="col-md-4">
                <label for="dentist_id" class="form-label small mb-1">Doctor</label>
                <select class="form-select" id="dentist_id" name="dentist_id">
                    <option value="">All doctors</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected((string) request('dentist_id') === (string) $doctor->id)>
                            Dr. {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill">Filter</button>
                @if ($filtering)
                    <a href="{{ route('medical-records.index') }}" class="btn btn-link px-1">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card">
        @if ($records->isEmpty())
            <div class="card-body text-center text-muted py-5">
                @if ($filtering)
                    <p class="mb-0">No medical records match these filters.</p>
                @else
                    <p class="mb-3">No medical records yet.</p>
                    <a href="{{ route('medical-records.create') }}" class="btn btn-primary">Add the first medical record</a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Diagnosis</th>
                            <th>Appointment</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                <td class="text-nowrap">{{ $record->created_at->format('d M Y') }}</td>
                                <td>
                                    @if ($record->patient)
                                        <a href="{{ route('patients.show', $record->patient) }}">
                                            {{ $record->patient->full_name }}
                                        </a>
                                        <div class="small text-muted">#{{ $record->patient->patient_number }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $record->dentist ? 'Dr. ' . $record->dentist->name : '—' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($record->diagnosis, 60) }}</td>
                                <td class="text-nowrap">
                                    @if ($record->appointment)
                                        <a href="{{ route('appointments.show', $record->appointment) }}">
                                            {{ $record->appointment->start_at->format('d M Y') }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('medical-records.show', $record) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('medical-records.edit', $record) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('medical-records.destroy', $record) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this medical record?');">
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
        {{ $records->links() }}
    </div>
@endsection

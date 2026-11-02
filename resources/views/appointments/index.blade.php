@extends('layouts.clinic')

@section('title', 'Appointments')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Appointments</h1>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">+ Book Appointment</a>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('appointments.index') }}" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="date" class="form-label small mb-1">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
            </div>

            <div class="col-md-3">
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

            <div class="col-md-3">
                <label for="status" class="form-label small mb-1">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All statuses</option>
                    @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill">Filter</button>
                <a href="{{ route('appointments.index', ['date' => now()->toDateString()]) }}"
                    class="btn btn-outline-secondary">Today</a>
                @if (request()->hasAny(['date', 'dentist_id', 'status']))
                    <a href="{{ route('appointments.index') }}" class="btn btn-link px-1">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card">
        @if ($appointments->isEmpty())
            <div class="card-body text-center text-muted py-5">
                @if (request()->hasAny(['date', 'dentist_id', 'status']))
                    <p class="mb-0">No appointments match these filters.</p>
                @else
                    <p class="mb-3">No appointments yet.</p>
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">Book the first appointment</a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date & Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td class="text-nowrap">
                                    <div class="fw-semibold">{{ $appointment->id }}</div>
                                </td>
                                <td class="text-nowrap">
                                    <div class="fw-semibold">{{ $appointment->start_at->format('D, d M Y') }}</div>
                                    <div class="small text-muted">
                                        {{ $appointment->start_at->format('H:i') }} –
                                        {{ $appointment->end_at->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    @if ($appointment->patient)
                                        <a href="{{ route('patients.show', $appointment->patient) }}">
                                            {{ $appointment->patient->full_name }}
                                        </a>
                                        <div class="small text-muted">#{{ $appointment->patient->patient_number }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $appointment->dentist ? 'Dr. ' . $appointment->dentist->name : '—' }}</td>
                                <td>{{ $appointment->service?->name ?? '—' }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $appointment->statusColor() }}">{{ $appointment->statusLabel() }}</span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('appointments.edit', $appointment) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this appointment?');">
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
        {{ $appointments->links() }}
    </div>
@endsection

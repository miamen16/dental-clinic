@extends('layouts.clinic')
@section('title', 'Invoices')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Invoices</h1>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">+ Add Invoice</a>
    </div>
    {{-- Filters --}}
    @php($filtering = request()->anyFilled(['search', 'status', 'patient']))
    <form method="GET" action="{{ route('invoices.index') }}" class="card card-body mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="search" class="form-control" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Invoice number, patient name or appointment service">
            </div>
            <div class="col-md-2">
                <label for="patient" class="form-label small mb-1">Patient</label>
                <select class="form-select" id="patient" name="patient">
                    <option value="">All</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" @selected((string) request('patient') === (string) $patient->id)>
                            {{ $patient->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label small mb-1">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All</option>
                    @foreach (\App\Models\Invoice::STATUSES as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-fill">Filter</button>
                @if ($filtering)
                    <a href="{{ route('invoices.index') }}" class="btn btn-link px-1">Clear</a>
                @endif
            </div>
        </div>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice Number</th>
                        <th>Patient</th>
                        <th>Appointment</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->patient?->full_name ?? '—' }}</td>
                            <td>
                                @if ($invoice->appointment)
                                    {{ $invoice->appointment->service?->name }}
                                    <span
                                        class="text-muted small">{{ $invoice->appointment->start_at?->format('Y-m-d H:i') }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ number_format($invoice->total, 2) }}</td>
                            <td>{{ ucfirst($invoice->status) }}</td>
                            <td>{{ $invoice->creator?->name ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('invoices.edit', $invoice) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="{{ route('invoices.show', $invoice) }}"
                                    class="btn btn-sm btn-outline-secondary">View</a>
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No invoices yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $invoices->links() }}
    </div>
@endsection

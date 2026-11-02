@extends('layouts.clinic')
@section('title', $invoice->invoice_number)
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Invoice: {{ $invoice->invoice_number }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">Back to Invoices</a>
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-semibold">Invoice Information</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Invoice Number</dt>
                <dd class="col-sm-9">{{ $invoice->invoice_number }}</dd>

                <dt class="col-sm-3">Patient</dt>
                <dd class="col-sm-9">{{ $invoice->patient?->full_name ?? '—' }}</dd>

                <dt class="col-sm-3">Appointment</dt>
                <dd class="col-sm-9">
                    @if ($invoice->appointment)
                        {{ $invoice->appointment->service?->name }}
                        <span class="text-muted small">{{ $invoice->appointment->start_at?->format('Y-m-d H:i') }}</span>
                    @else
                        —
                    @endif
                </dd>
                <dt class="col-sm-3">Subtotal</dt>
                <dd class="col-sm-9">{{ number_format($invoice->subtotal, 2) }}</dd>

                <dt class="col-sm-3">Discount</dt>
                <dd class="col-sm-9">{{ number_format($invoice->discount, 2) }}</dd>

                <dt class="col-sm-3">Tax</dt>
                <dd class="col-sm-9">{{ number_format($invoice->tax, 2) }}</dd>
                <dt class="col-sm-3">Total Amount</dt>
                <dd class="col-sm-9">{{ number_format($invoice->total, 2) }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ ucfirst($invoice->status) }}</dd>

                <dt class="col-sm-3">Created By</dt>
                <dd class="col-sm-9">{{ $invoice->creator?->name ?? '—' }}</dd>

                <dt class=" col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $invoice->created_at?->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>
    <div class="card">
        <div class="card-header fw-semibold">Invoice Items</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td>{{ $item->service?->name ?? '—' }}</td>
                            <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layouts.clinic')

@section('title', $tax->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Tax: {{ $tax->name }}</h1>
            @if ($tax->is_active)
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-secondary">Inactive</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('taxes.index') }}" class="btn btn-outline-secondary">Back to Taxes</a>
            <a href="{{ route('taxes.edit', $tax) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('taxes.destroy', $tax) }}" method="POST"
                onsubmit="return confirm('Delete this tax?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Rate</div>
                    <div class="display-6 fw-semibold">
                        @if ($tax->type === 'Percentage')
                            {{ rtrim(rtrim(number_format($tax->rate, 2), '0'), '.') }}%
                        @else
                            {{ number_format($tax->rate, 2) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Type</div>
                    <div class="display-6 fw-semibold">{{ $tax->type }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header fw-semibold">Tax Information</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9">{{ $tax->name }}</dd>

                <dt class="col-sm-3">Service</dt>
                <dd class="col-sm-9">
                    @if ($tax->service)
                        <a href="{{ route('services.show', $tax->service) }}">{{ $tax->service->name }}</a>
                        <span class="text-muted">({{ number_format($tax->service->price, 2) }})</span>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9">{{ $tax->description ?: '—' }}</dd>

                <dt class="col-sm-3">Created</dt>
                <dd class="col-sm-9">{{ $tax->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>

                <dt class="col-sm-3">Last Updated</dt>
                <dd class="col-sm-9">{{ $tax->updated_at?->format('Y-m-d H:i') ?? '—' }}</dd>
            </dl>
        </div>
    </div>
@endsection

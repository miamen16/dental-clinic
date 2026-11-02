@extends('layouts.clinic')
@section('title', 'Taxes')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Taxes</h1>
        <a href="{{ route('taxes.create') }}" class="btn btn-primary">+ Add Tax</a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Rate</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($taxes as $tax)
                        <tr>
                            <td>{{ $tax->name }}</td>
                            <td>{{ $tax->type }}</td>
                            <td>
                                @if ($tax->type === 'Percentage')
                                    {{ rtrim(rtrim(number_format($tax->rate, 2), '0'), '.') }}%
                                @else
                                    {{ number_format($tax->rate, 2) }}
                                @endif
                            </td>
                            <td>{{ $tax->service?->name ?? '—' }}</td>
                            <td>
                                @if ($tax->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('taxes.show', $tax) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ route('taxes.edit', $tax) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('taxes.destroy', $tax) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this tax?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No taxes yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $taxes->links() }}
    </div>
@endsection

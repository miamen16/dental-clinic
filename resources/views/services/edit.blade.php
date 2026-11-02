@extends('layouts.clinic')

@section('title', 'Edit ' . $service->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Service</h1>
            <span class="text-muted">{{ $service->name }}</span>
        </div>
        <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary">View Service</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
        </div>
    @endif

    <form action="{{ route('services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')

        @include('services._form', ['service' => $service])

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection

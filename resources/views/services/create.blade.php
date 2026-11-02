@extends('layouts.clinic')

@section('title', 'Add Service')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Add Service</h1>
        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Back to Services</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
        </div>
    @endif

    <form action="{{ route('services.store') }}" method="POST">
        @csrf

        @include('services._form', ['service' => null])

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Add Service</button>
            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection

@extends('layouts.clinic')
@section('title', 'Create Tax')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Create Tax</h1>
        <a href="{{ route('taxes.index') }}" class="btn btn-outline-secondary">Back to Taxes</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('taxes.store') }}" method="POST">
                @csrf
                @include('taxes._form')
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Add Tax</button>
                    <a href="{{ route('taxes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

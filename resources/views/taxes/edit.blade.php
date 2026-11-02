@extends('layouts.clinic')
@section('title', 'Edit Tax')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Tax: {{ $tax->name }}</h1>
        <a href="{{ route('taxes.index') }}" class="btn btn-outline-secondary">Back to Taxes</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('taxes.update', $tax) }}" method="POST">
                @csrf
                @method('PUT')
                @include('taxes._form', ['tax' => $tax])
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Tax</button>
                    <a href="{{ route('taxes.show', $tax) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

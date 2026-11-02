@extends('layouts.clinic')

@section('title', 'Edit Medical Record')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Medical Record</h1>
            <span class="text-muted">#{{ $record->id }}</span>
        </div>
        <a href="{{ route('medical-records.show', $record) }}" class="btn btn-outline-secondary">View Medical Record</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
        </div>
    @endif

    <form action="{{ route('medical-records.update', $record) }}" method="POST">
        @csrf
        @method('PUT')

        @include('medical-records._form', ['record' => $record])

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('medical-records.show', $record) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection

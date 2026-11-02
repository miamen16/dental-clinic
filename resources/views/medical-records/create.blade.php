@extends('layouts.clinic')

@section('title', 'Add Medical Record')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Add Medical Record</h1>
        <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">Back to Medical Records</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
        </div>
    @endif

    @if ($patients->isEmpty() || $doctors->isEmpty())
        <div class="alert alert-warning">
            <strong>You can't add a medical record yet.</strong>
            <ul class="mb-0 mt-1">
                @if ($patients->isEmpty())
                    <li>No patients — <a href="{{ route('patients.create') }}">add a patient</a>.</li>
                @endif
                @if ($doctors->isEmpty())
                    <li>No active doctors — <a href="{{ route('doctors.create') }}">add a doctor</a>.</li>
                @endif
            </ul>
        </div>
    @endif

    <form action="{{ route('medical-records.store') }}" method="POST">
        @csrf

        @include('medical-records._form', ['record' => null])

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Add Medical Record</button>
            <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection

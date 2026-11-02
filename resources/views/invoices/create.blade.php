@extends('layouts.clinic')
@section('title', 'Create Invoice')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Create Invoice</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                @include('invoices.partials.form')
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection

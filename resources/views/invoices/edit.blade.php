@extends('layouts.clinic')
@section('title', 'Edit Invoice')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Invoice: {{ $invoice->invoice_number }}</h1>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">Back to Invoices</a>
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
            <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                @csrf
                @method('PUT')
                @include('invoices.partials.form', ['invoice' => $invoice])
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Invoice</button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

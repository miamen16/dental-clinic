@php
    // $tax is null on the create page
    $tax ??= null;
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $tax?->name) }}" maxlength="255" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
        <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id"
            required>
            <option value="">-- Select service --</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}" @selected((string) old('service_id', $tax?->service_id) === (string) $service->id)>
                    {{ $service->name }} — {{ number_format($service->price, 2) }}
                </option>
            @endforeach
        </select>
        @error('service_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
            <option value="">-- Select --</option>
            @foreach (\App\Models\Tax::TYPES as $type)
                <option value="{{ $type }}" @selected(old('type', $tax?->type) === $type)>{{ $type }}</option>
            @endforeach
        </select>
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="rate" class="form-label">Rate <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('rate') is-invalid @enderror" id="rate" name="rate"
            value="{{ old('rate', $tax?->rate) }}" min="0" max="100" step="0.01" required>
        <div class="form-text">A percentage for Percentage taxes, an amount for Fixed taxes.</div>
        @error('rate')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
            <option value="1" @selected((string) old('is_active', (int) ($tax?->is_active ?? 1)) === '1')>Active</option>
            <option value="0" @selected((string) old('is_active', (int) ($tax?->is_active ?? 1)) === '0')>Inactive</option>
        </select>
        @error('is_active')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
            rows="3">{{ old('description', $tax?->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

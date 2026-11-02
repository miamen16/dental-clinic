{{-- Shared by create and edit. Pass $service (null when creating). --}}
<div class="card mb-4">
    <div class="card-header fw-semibold">Service Information</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                    name="name" value="{{ old('name', $service?->name) }}" placeholder="e.g. Teeth Cleaning"
                    required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="code" class="form-label">Service Code</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                    name="code" value="{{ old('code', $service?->code) }}" placeholder="e.g. CLN-01">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                    name="price" value="{{ old('price', $service?->price) }}" min="0" step="0.01" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label d-block">Status</label>
                {{-- unchecked switches send nothing, so the hidden input sends 0 --}}
                <input type="hidden" name="is_active" value="0">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox"
                        role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $service?->is_active ?? true ? '1' : '0') === '1')>
                    <label class="form-check-label" for="is_active">Active (can be booked)</label>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                    rows="4" placeholder="What the service includes, how long it takes...">{{ old('description', $service?->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label for="tax_id" class="form-label">Tax</label>
                <select class="form-control @error('tax_id') is-invalid @enderror" id="tax_id" name="tax_id">
                    <option value="">Select a tax</option>
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id }}" @selected(old('tax_id', $service?->tax_id) == $tax->id)>{{ $tax->name }}</option>
                    @endforeach
                </select>
                @error('tax_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

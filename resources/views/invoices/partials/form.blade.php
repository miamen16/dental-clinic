@php
    // $invoice is null on the create page
    $invoice ??= null;

    // Existing items as form rows; the discount isn't stored per item, so derive it from the line total
    $items = $invoice?->items->map(fn ($item) => [
        'service_id' => $item->service_id,
        'quantity' => $item->quantity,
        'discount_amount' => round($item->unit_price * $item->quantity - $item->line_total, 2),
    ])->all() ?: [['service_id' => '', 'quantity' => 1, 'discount_amount' => 0]];
@endphp
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                        <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id"
                            name="patient_id" required>
                            <option value="">-- Select patient --</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}" @selected(old('patient_id', $invoice?->patient_id) == $patient->id)>
                                    {{ $patient->full_name }} ({{ $patient->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="appointment_id" class="form-label">Appointment (optional)</label>
                        <select class="form-select @error('appointment_id') is-invalid @enderror" id="appointment_id"
                            name="appointment_id">
                            <option value="">-- Select appointment --</option>
                            @foreach ($appointments as $appointment)
                                <option value="{{ $appointment->id }}" @selected(old('appointment_id', $invoice?->appointment_id) == $appointment->id)>
                                    {{ $appointment->service?->name }} on {{ $appointment->start_at?->format('Y-m-d H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('appointment_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('invoice_date') is-invalid @enderror"
                            id="invoice_date" name="invoice_date"
                            value="{{ old('invoice_date', $invoice?->issued_at?->toDateString() ?? now()->toDateString()) }}" required>
                        @error('invoice_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date"
                            name="due_date" value="{{ old('due_date', $invoice?->due_at?->toDateString() ?? now()->addDays(30)->toDateString()) }}" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            @foreach (\App\Models\Invoice::STATUSES as $status)
                                <option value="{{ $status }}" @selected(old('status', $invoice?->status ?? 'pending') === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h2 class="h5 mt-4">Items</h2>
                @error('items')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Service</th>
                            <th style="width: 120px">Quantity</th>
                            <th style="width: 160px">Discount</th>
                            <th style="width: 60px"></th>
                        </tr>
                    </thead>
                    <tbody id="invoice-items">
                        @foreach (old('items', $items) as $i => $item)
                            <tr>
                                <td>
                                    <select class="form-select @error("items.$i.service_id") is-invalid @enderror"
                                        name="items[{{ $i }}][service_id]" required>
                                        <option value="">-- Select service --</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" @selected((string) ($item['service_id'] ?? '') === (string) $service->id)>
                                                {{ $service->name }} — {{ number_format($service->price, 2) }}
                                                @if ($service->tax)
                                                    (+ {{ $service->tax->name }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control @error("items.$i.quantity") is-invalid @enderror"
                                        name="items[{{ $i }}][quantity]" value="{{ $item['quantity'] ?? 1 }}"
                                        min="1" required>
                                </td>
                                <td>
                                    <input type="number"
                                        class="form-control @error("items.$i.discount_amount") is-invalid @enderror"
                                        name="items[{{ $i }}][discount_amount]"
                                        value="{{ $item['discount_amount'] ?? 0 }}" min="0" step="0.01">
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item">&times;</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-outline-primary mb-4" id="add-item">+ Add item</button>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $invoice?->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

    <script>
        (function () {
            const body = document.getElementById('invoice-items');
            let nextIndex = body.rows.length;

            document.getElementById('add-item').addEventListener('click', function () {
                const row = body.rows[0].cloneNode(true);
                row.querySelectorAll('select, input').forEach(function (field) {
                    field.name = field.name.replace(/items\[\d+\]/, 'items[' + nextIndex + ']');
                    field.classList.remove('is-invalid');
                    if (field.tagName === 'SELECT') field.value = '';
                    else field.value = field.name.endsWith('[quantity]') ? 1 : 0;
                });
                body.appendChild(row);
                nextIndex++;
            });

            body.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-item') && body.rows.length > 1) {
                    e.target.closest('tr').remove();
                }
            });
        })();
    </script>

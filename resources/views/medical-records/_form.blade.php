{{-- Shared by create and edit. Pass $record (null when creating) and $defaults (prefilled ids when creating). --}}
<div class="card mb-4">
    <div class="card-header fw-semibold">Patient & Doctor</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id"
                    required>
                    <option value="">-- Select patient --</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" @selected((string) old('patient_id', $record?->patient_id ?? $defaults['patient_id'] ?? '') === (string) $patient->id)>
                            {{ $patient->full_name }} (#{{ $patient->patient_number }})
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="dentist_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                <select class="form-select @error('dentist_id') is-invalid @enderror" id="dentist_id" name="dentist_id"
                    required>
                    <option value="">-- Select doctor --</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected((string) old('dentist_id', $record?->dentist_id ?? $defaults['dentist_id'] ?? '') === (string) $doctor->id)>
                            Dr. {{ $doctor->name }}{{ $doctor->specialty ? ' — ' . $doctor->specialty : '' }}
                        </option>
                    @endforeach
                </select>
                @error('dentist_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="appointment_id" class="form-label">Related Appointment</label>
                <select class="form-select @error('appointment_id') is-invalid @enderror" id="appointment_id"
                    name="appointment_id">
                    <option value="">-- None --</option>
                    @foreach ($appointments as $appointment)
                        <option value="{{ $appointment->id }}" @selected((string) old('appointment_id', $record?->appointment_id ?? $defaults['appointment_id'] ?? '') === (string) $appointment->id)>
                            {{ $appointment->start_at->format('d M Y, H:i') }} — {{ $appointment->patient?->full_name ?? 'Unknown patient' }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Optional. Must be an appointment of the selected patient.</div>
                @error('appointment_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header fw-semibold">Clinical Details</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <label for="diagnosis" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                <textarea class="form-control @error('diagnosis') is-invalid @enderror" id="diagnosis" name="diagnosis"
                    rows="3" required>{{ old('diagnosis', $record?->diagnosis) }}</textarea>
                @error('diagnosis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="clinical_notes" class="form-label">Clinical Notes</label>
                <textarea class="form-control @error('clinical_notes') is-invalid @enderror" id="clinical_notes"
                    name="clinical_notes" rows="5">{{ old('clinical_notes', $record?->clinical_notes) }}</textarea>
                @error('clinical_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="recommendations" class="form-label">Recommendations</label>
                <textarea class="form-control @error('recommendations') is-invalid @enderror" id="recommendations"
                    name="recommendations" rows="3">{{ old('recommendations', $record?->recommendations) }}</textarea>
                @error('recommendations')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<?php

namespace App\Http\Controllers\MedicalRecords;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalRecords\StoreMedicalRecordRequest;
use App\Http\Requests\MedicalRecords\UpdateMedicalRecordRequest;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicalRecordController extends Controller
{
    /**
     * Patients, doctors and appointments for the form dropdowns.
     */
    private function formData(?MedicalRecord $record = null): array
    {
        return [
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
            // when editing, keep the current doctor even if they were deactivated since
            'doctors' => User::doctors()
                ->where(fn ($q) => $q->where('is_active', true)->orWhere('id', $record?->dentist_id))
                ->orderBy('name')->get(),
            // appointments without a record yet, plus the one already linked to this record
            'appointments' => Appointment::with('patient')
                ->where(fn ($q) => $q->whereDoesntHave('medicalRecord')->orWhere('id', $record?->appointment_id))
                ->latest('start_at')
                ->get(),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $records = MedicalRecord::with(['patient', 'dentist', 'appointment'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(fn ($q) => $q
                    ->where('diagnosis', 'like', $search)
                    ->orWhereHas('patient', fn ($p) => $p
                        ->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('patient_number', 'like', $search)));
            })
            ->when($request->filled('dentist_id'), fn ($q) => $q->where('dentist_id', $request->dentist_id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Doctors for the filter dropdown
        $doctors = User::doctors()->orderBy('name')->get();

        return view('medical-records.index', compact('records', 'doctors'));
    }

    /**
     * Show the form for creating a new resource.
     * Accepts ?patient_id= or ?appointment_id= to prefill the form.
     */
    public function create(Request $request): View
    {
        $appointment = $request->filled('appointment_id')
            ? Appointment::find($request->appointment_id)
            : null;

        $defaults = [
            'patient_id' => $appointment?->patient_id ?? $request->patient_id,
            'appointment_id' => $appointment?->id,
            // a doctor writing a record is usually writing their own
            'dentist_id' => $appointment?->dentist_id ?? ($request->user()->isDoctor() ? $request->user()->id : null),
        ];

        return view('medical-records.create', ['defaults' => $defaults] + $this->formData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicalRecordRequest $request): RedirectResponse
    {
        $record = MedicalRecord::create($request->validated());

        return redirect()->route('medical-records.show', $record)->with('success', 'Medical record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $record = MedicalRecord::with(['patient', 'dentist', 'appointment.service'])->findOrFail($id);

        return view('medical-records.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $record = MedicalRecord::findOrFail($id);

        return view('medical-records.edit', ['record' => $record] + $this->formData($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicalRecordRequest $request, string $id): RedirectResponse
    {
        $record = MedicalRecord::findOrFail($id);
        $record->update($request->validated());

        return redirect()->route('medical-records.show', $record)->with('success', 'Medical record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $record = MedicalRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('medical-records.index')->with('success', 'Medical record deleted successfully.');
    }
}

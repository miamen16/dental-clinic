<?php

namespace App\Http\Controllers\Patients;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Patient;
use App\Http\Requests\Patients\StorePatientRequest;
use App\Http\Requests\Patients\UpdatePatientRequest;
use Illuminate\View\View;
class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request): View
    {
        
        $patients = Patient::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(fn ($q) => $q->where('first_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhere('patient_number', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('phone_number', 'like', $search));
            })
            ->when($request->filled('gender'), fn ($q) => $q->where('gender', $request->gender))
            ->when($request->filled('blood_type'), fn ($q) => $q->where('blood_type', $request->blood_type))
            ->orderBy('first_name')
            ->paginate(20)
            ->withQueryString();

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StorePatientRequest $request
    ): RedirectResponse {
        Patient::create($request->validated());

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::with(['medicalRecords' => fn ($q) => $q->with('dentist')->latest()])->findOrFail($id);
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->update($request->validated());
        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}

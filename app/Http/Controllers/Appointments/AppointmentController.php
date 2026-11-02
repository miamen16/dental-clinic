<?php

namespace App\Http\Controllers\Appointments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Appointments\StoreAppointmentRequest;
use App\Http\Requests\Appointments\UpdateAppointmentRequest;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
class AppointmentController extends Controller
{
    /**
     * Patients, active doctors and active services for the form dropdowns.
     */
    private function formData(?Appointment $appointment = null): array
    {
        return [
            // Retrieve patients, doctors, and services for the form dropdowns
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
            // when editing, keep the current doctor/service even if they were deactivated since
            'doctors' => User::doctors()
                ->where(fn ($q) => $q->where('is_active', true)->orWhere('id', $appointment?->dentist_id))
                ->orderBy('name')->get(),
                // when editing, keep the current doctor/service even if they were deactivated since
            'services' => Service::where(fn ($q) => $q->where('is_active', true)->orWhere('id', $appointment?->service_id))
                ->orderBy('name')->get(),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Retrieve appointments, filtered by the options chosen on the page
        $appointments = Appointment::with(['patient', 'dentist', 'service'])
            ->when($request->filled('date'), fn ($q) => $q->whereDate('start_at', $request->date))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('dentist_id'), fn ($q) => $q->where('dentist_id', $request->dentist_id))
            ->orderBy('start_at')
            ->paginate(20)
            ->withQueryString();
        // Doctors for the filter dropdown
        $doctors = User::doctors()->orderBy('name')->get();
        // Return the view with the appointments data
        return view('appointments.index', compact('appointments', 'doctors'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form to create a new appointment
        return view('appointments.create', $this->formData());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        // Validate and create a new appointment record
        $validatedData = $request->validated();
        // Combine date and time into a single datetime for start_at
        $validatedData['start_at'] = $validatedData['date'] . ' ' .$validatedData['time'];
        // Set end_at to 1 hour after start_at
        $validatedData['end_at'] = date('Y-m-d H:i:s', strtotime($validatedData['start_at'] . ' +1 hour'));
        // Record who booked the appointment
        $validatedData['created_by'] = $request->user()->id;
        // Create the appointment record
        Appointment::create($validatedData);
        // Redirect to the appointment index page with a success message
        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Retrieve the appointment record by ID
        $appointment = Appointment::with(['patient', 'dentist', 'service', 'creator', 'medicalRecord'])->findOrFail($id);
        // Show the details of the appointment
        return view('appointments.show', compact('appointment'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Retrieve the appointment record by ID for editing
        $appointment = Appointment::findOrFail($id);
        // Show the edit form with the appointment's data
        return view('appointments.edit', ['appointment' => $appointment] + $this->formData($appointment));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, string $id)
    {
        // Retrieve the appointment record by ID
        $appointment = Appointment::findOrFail($id);
        // Validate and update the appointment record
        $validatedData = $request->validated();
        // Combine date and time into a single datetime for start_at
        $validatedData['start_at'] = $validatedData['date'] . ' ' .$validatedData['time'];
        // Set end_at to 1 hour after start_at
        $validatedData['end_at'] = date('Y-m-d H:i:s', strtotime($validatedData['start_at'] . ' +1 hour'));
        // Update the appointment record
        $appointment->update($validatedData);
        // Redirect to the appointment show page with a success message
        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        // Retrieve the appointment record by ID
        $appointment = Appointment::findOrFail($id);
        // Delete the appointment record
        $appointment->delete();
        // Redirect to the appointment index page with a success message
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }
    
}

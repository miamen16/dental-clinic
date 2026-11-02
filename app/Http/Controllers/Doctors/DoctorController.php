<?php

namespace App\Http\Controllers\Doctors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Doctors\StoreDoctorRequest;
use App\Http\Requests\Doctors\UpdateDoctorRequest;
class DoctorController extends Controller
{
    //
    public function index(Request $request): View
    {
        // Retrieve doctors, filtered by the options chosen on the page
        $doctors = User::doctors()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(fn ($q) => $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('id', 'like', $search)
                    ->orWhere('phone', 'like', $search));
            })
            // Filter by specialty, status, and gender if those filters are applied
            ->when($request->filled('specialty'), fn ($q) => $q->where('specialty', $request->specialty))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'))
            ->when($request->filled('gender'), fn ($q) => $q->where('gender', $request->gender))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        // Pass the doctors to the view
        return view('doctors.index', compact('doctors'));
    }
    public function create(): view
    {
        // Show the form to create a new doctor
        return(view('doctors.create'));
    }
    public function store(StoreDoctorRequest $request): RedirectResponse
    {
        // Validate and create a new doctor (user) record
        $validatedData = $request->validated();
        // Set the role to 'doctor' and hash the password before saving
        $validatedData['role'] = User::ROLE_DOCTOR; 
        // Ensure the role is set to 'doctor'
        $validatedData['password'] = bcrypt($validatedData['password']); // Hash the password
       // Create the doctor record
        User::create($validatedData);
        // Redirect to the doctors index page with a success message
        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');
    }
    public function show($id)
    {
        // Retrieve the doctor (user) record by ID
        $doctor = User::doctors()->findOrFail($id);
        // Show the details of the doctor
        return view('doctors.show', compact('doctor'));
    }
    public function edit($id)
    {
        // Retrieve the doctor (user) record by ID for editing
        $doctor = User::doctors()->findOrFail($id);
        // Show the edit form with the doctor's data
        return view('doctors.edit', compact('doctor'));
    }
    public function update(UpdateDoctorRequest $request, $id): RedirectResponse
    {
        // Retrieve the doctor (user) record by ID
        $doctor = User::doctors()->findOrFail($id);
        // Validate the request data
        $validatedData = $request->validated();
        // If password is provided, hash it; otherwise, remove it from the validated data
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
         // Update the doctor record with the validated data
        $doctor->update($validatedData);
         // Redirect back to the doctors index with a success message
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }
    public function toggleStatus($id): RedirectResponse
    {
        // Flip the doctor between active and inactive
        $doctor = User::doctors()->findOrFail($id);
        // Toggle the is_active status of the doctor
        // Update the doctor's is_active status to the opposite of its current value
        $doctor->update(['is_active' => ! $doctor->is_active]);
        // Determine the new status for the success message
        $status = $doctor->is_active ? 'activated' : 'deactivated';
        // Redirect back to the previous page with a success message indicating the new status
        return back()->with('success', "{$doctor->name} {$status}.");
    }
    public function destroyAll(): RedirectResponse
    {
        // Delete all users with the role of 'doctor'
        User::where('role', 'doctor')->delete();
        // Redirect back to the doctors index with a success message
        return redirect()->route('doctors.index')->with('success', 'All doctors deleted successfully.');
    }
    public function destroy($id): redirectResponse
    {
        // Retrieve the doctor (user) record by ID
        $doctor = User::doctors()->findOrFail($id);
        // Delete the doctor record
        $doctor->delete();
        // Redirect back to the doctors index with a success message
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}

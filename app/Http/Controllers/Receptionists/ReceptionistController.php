<?php
namespace App\Http\Controllers\Receptionists;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\View\View;
use App\Http\Requests\Receptionists\StoreReceptionistRequest;
use App\Http\Requests\Receptionists\UpdateReceptionistRequest;
class ReceptionistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request): View
    {
        // Retrieve receptionists, filtered by the options chosen on the page
        $receptionists = User::receptionists()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(fn ($q) => $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('id', 'like', $search)
                    ->orWhere('phone', 'like', $search));
            })
            // Filter by status and gender if those filters are applied
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'))
            ->when($request->filled('gender'), fn ($q) => $q->where('gender', $request->gender))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
            // Pass the receptionists to the view
        return view('receptionists.index', compact('receptionists'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form to create a new receptionist
        return view('receptionists.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReceptionistRequest $request)
    {
        // Validate and create a new receptionist record
        $data = $request->validated();
        // Set the role to receptionist and hash the password before saving
        $data['role'] = User::ROLE_RECEPTIONIST;
        // Hash the password before saving
        $data['password'] = bcrypt($data['password']);
        // Create the receptionist
        User::create($data);
        // Redirect to the receptionist index page with a success message
        return redirect()->route('receptionists.index')->with('success', 'Receptionist created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the receptionist by ID and show their details
        $receptionist = User::receptionists()->findOrFail($id);
        // Show the receptionist details view
        return view('receptionists.show', compact('receptionist'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the receptionist by ID and show the edit form
        $receptionist = User::receptionists()->findOrFail($id);
        // Show the edit form with the receptionist's data
        return view('receptionists.edit', compact('receptionist'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReceptionistRequest $request, string $id)
    {
        // Find the receptionist by ID and update their details
        $receptionist = User::receptionists()->findOrFail($id);
        // Validate the request data
        $data = $request->validated();

        // If password is provided, hash it; otherwise keep the current one
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        // Update the receptionist's details
        $receptionist->update($data);
        // Redirect to the receptionist's detail page with a success message
        return redirect()->route('receptionists.show', $receptionist)->with('success', 'Receptionist updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the receptionist by ID and delete their record
        $receptionist = User::receptionists()->findOrFail($id);
        // Delete the receptionist
        $receptionist->delete();
        // Redirect to the receptionist index page with a success message
        return redirect()->route('receptionists.index')->with('success', 'Receptionist deleted successfully.');
    }
}

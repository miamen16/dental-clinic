<?php

namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Services\StoreServiceRequest;
use App\Http\Requests\Services\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Tax;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Retrieve all services, with how many appointments each one has
        $services = Service::query()
            ->withCount('appointments')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(
                    fn($q) => $q->where('name', 'like', $search)
                        ->orWhere('code', 'like', $search)
                );
            })
            // Filter by code and status if those filters are applied
            ->when($request->filled('code'), function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->code . '%');
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $isActive = $request->status === 'active';
                $q->where('is_active', $isActive);
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        // Pass the services to the view
        return view('services.index', compact('services'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Show the form to create a new service
        $taxes = Tax::all();
        // Pass the taxes to the view for selection in the form
        return view('services.create', compact('taxes'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request): RedirectResponse
    {
        // Validate and create a new service record
        Service::create($request->validated());
        // Redirect to the services index page with a success message
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Retrieve the service record by ID, with its appointment count
        $service = Service::withCount('appointments')->findOrFail($id);
        // Show the details of the service
        return view('services.show', compact('service'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Retrieve the service record by ID for editing
        $service = Service::findOrFail($id);
        $taxes = Tax::all();
        // Show the edit form with the service's data
        return view('services.edit', compact('service','taxes'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $id): RedirectResponse
    {
        // Retrieve the service record by ID
        $service = Service::findOrFail($id);
        // Validate and update the service record
        $service->update($request->validated());
        // Redirect to the service show page with a success message
        return redirect()->route('services.show', $service)->with('success', 'Service updated successfully.');
    }
    /**
     * Switch the service between active and inactive.
     */
    public function toggleStatus(string $id): RedirectResponse
    {
        // Inactive services can't be booked for new appointments
        $service = Service::findOrFail($id);
        // Flip the service's active status
        $service->update(['is_active' => ! $service->is_active]);
        // Determine the new status for the success message
        $status = $service->is_active ? 'activated' : 'deactivated';
        // Redirect back to the previous page with a success message indicating the new status
        return back()->with('success', "{$service->name} {$status}.");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        // Retrieve the service record by ID
        $service = Service::findOrFail($id);
        // Soft delete: old appointments and invoices keep their service
        $service->delete();
        // Redirect to the services index page with a success message
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}

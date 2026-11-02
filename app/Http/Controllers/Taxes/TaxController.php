<?php

namespace App\Http\Controllers\Taxes;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Tax;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Taxes\StoreTaxRequest;
use App\Http\Requests\Taxes\UpdateTaxRequest;
class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Retrieve all taxes with their associated services, paginated
        $taxes = Tax::with('service')->orderBy('name')->paginate(15);
        // Pass the taxes to the view for display
        return view('taxes.index', compact('taxes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // get all active services to display in the form
        $services = Service::where('is_active', true)->orderBy('name')->get();
        // Pass the active services to the view for selection in the form
        return view('taxes.create', compact('services'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaxRequest $request): RedirectResponse
    {
        // Validate and create a new tax record
        $tax = Tax::create($request->validated());
        // Redirect to the tax show page with a success message
        return redirect()->route('taxes.show', $tax)->with('success', 'Tax created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Tax $tax): View
    {
        // Load the associated service for the tax
        $tax->load('service');
        // Pass the tax to the view for display
        return view('taxes.show', compact('tax'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tax $tax): View
    {
        // Active services, plus this tax's current service even if it has since been deactivated
        $services = Service::where('is_active', true)
            ->orWhere('id', $tax->service_id)
            ->orderBy('name')
            ->get();
        // Pass the tax and services to the view for editing
        return view('taxes.edit', compact('tax', 'services'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxRequest $request, Tax $tax): RedirectResponse
    {
        // Validate and update the tax record
        $tax->update($request->validated());
        // Redirect to the tax show page with a success message
        return redirect()->route('taxes.show', $tax)->with('success', 'Tax updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax): RedirectResponse
    {
        // Services using this tax are unlinked, and invoices keep their tax snapshot (both foreign keys null on delete)
        $tax->delete();
        // Redirect to the taxes index page with a success message
        return redirect()->route('taxes.index')->with('success', 'Tax deleted successfully.');
    }
}

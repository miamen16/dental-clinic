<?php

namespace App\Http\Controllers\Invoices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Invoices\StoreInvoiceRequest;
use App\Http\Requests\Invoices\UpdateInvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemTax;
use Illuminate\Support\Str;
use App\Models\Tax;
use App\Models\Service;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
       
        $invoices = Invoice::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(fn ($q) => $q->where('invoice_number', 'like', $search)
                    ->orWhereHas('patient', fn ($q) => $q->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$search])
                        ->orWhere('patient_number', 'like', $search))
                    ->orWhereHas('appointment.service', fn ($q) => $q->where('name', 'like', $search)));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('patient'), fn ($q) => $q->where('patient_id', $request->patient))
            ->with(['patient', 'appointment.service', 'creator'])
            ->orderBy('issued_at', 'desc')
            ->paginate(20)
            ->withQueryString();
        $patients = Patient::orderBy('first_name')->orderBy('last_name')->get();
        return view('invoices.index', compact('invoices', 'patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->orderBy('last_name')->get();
        $appointments = Appointment::with('service')->latest('start_at')->get();
        $services = Service::with('tax')->where('is_active', true)->orderBy('name')->get();
        return view('invoices.create', compact('patients', 'appointments', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $items = $data['items'];

        // Use a transaction so the invoice, its items and their taxes are saved together or not at all
        $invoice = DB::transaction(function () use ($data, $items) {
            // 1. Create the invoice; totals are filled in after the items are calculated
            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5)),
                'patient_id' => $data['patient_id'],
                'appointment_id' => $data['appointment_id'] ?? null,
                'created_by' => Auth::id(),
                'issued_at' => $data['invoice_date'],
                'due_at' => $data['due_date'],
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->saveItems($invoice, $items);

            return $invoice;
        });

        return redirect()->route('invoices.index')
            ->with('success', "Invoice {$invoice->invoice_number} created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::with(['patient', 'appointment.service', 'creator', 'items.taxes'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');
        $patients = Patient::orderBy('first_name')->orderBy('last_name')->get();
        $appointments = Appointment::with('service')->latest('start_at')->get();
        // Active services, plus any already on this invoice even if they've since been deactivated
        $services = Service::with('tax')
            ->where('is_active', true)
            ->orWhereIn('id', $invoice->items->pluck('service_id')->filter())
            ->orderBy('name')
            ->get();
        return view('invoices.edit', compact('invoice', 'patients', 'appointments', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($invoice, $data) {
            $invoice->update([
                'patient_id' => $data['patient_id'],
                'appointment_id' => $data['appointment_id'] ?? null,
                'issued_at' => $data['invoice_date'],
                'due_at' => $data['due_date'],
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Replace the items; their taxes are removed by the cascading foreign key
            $invoice->items()->delete();
            $this->saveItems($invoice, $data['items']);
        });

        return redirect()->route('invoices.index')
            ->with('success', "Invoice {$invoice->invoice_number} updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', "Invoice {$invoice->invoice_number} deleted successfully.");
    }

    /**
     * Create the invoice items (with tax snapshots) and save the calculated totals on the invoice.
     */
    private function saveItems(Invoice $invoice, array $items): void
    {
        $invoiceSubtotal = 0;
        $invoiceDiscountTotal = 0;
        $invoiceTaxTotal = 0;

        foreach ($items as $index => $item) {
            $service = Service::with('tax')->findOrFail($item['service_id']);

            $quantity = (int) $item['quantity'];
            $discount = (float) ($item['discount_amount'] ?? 0);
            $lineGross = $service->price * $quantity;

            if ($discount > $lineGross) {
                throw ValidationException::withMessages([
                    "items.$index.discount_amount" => "Discount cannot exceed the total for {$service->name}.",
                ]);
            }

            // Line total after discount, before tax
            $lineTotal = $lineGross - $discount;

            $invoiceItem = $invoice->items()->create([
                'service_id' => $service->id,
                'description' => $service->name,
                'quantity' => $quantity,
                'unit_price' => $service->price,
                'line_total' => $lineTotal,
            ]);

            $invoiceSubtotal += $lineGross;
            $invoiceDiscountTotal += $discount;

            // Calculate tax if the service has one (stored as a snapshot on the item)
            $tax = $service->tax;
            if ($tax && $tax->is_active) {
                $taxAmount = $tax->type === 'Percentage'
                    ? round($lineTotal * $tax->rate / 100, 2)
                    : round((float) $tax->rate, 2); // Fixed amount per line

                $invoiceItem->taxes()->create([
                    'tax_id' => $tax->id,
                    'tax_name' => $tax->name,
                    'tax_type' => $tax->type,
                    'tax_rate' => $tax->rate,
                    'tax_amount' => $taxAmount,
                ]);
                $invoiceTaxTotal += $taxAmount;
            }
        }

        $invoice->update([
            'subtotal' => $invoiceSubtotal,
            'discount' => $invoiceDiscountTotal,
            'tax' => $invoiceTaxTotal,
            'total' => $invoiceSubtotal - $invoiceDiscountTotal + $invoiceTaxTotal,
        ]);
    }
}

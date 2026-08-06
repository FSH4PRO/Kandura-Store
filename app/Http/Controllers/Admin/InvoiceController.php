<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Invoices index (admin side)
     */
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $this->authorize('viewAny', Invoice::class);
        

        $invoices = Invoice::with(['order.customer.user'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('invoice_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('order.customer.user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('content.invoices.index', [
            'invoices' => $invoices,
            'filters'  => $request->only(['search']),
        ]);
    }

    /**
     * Show single invoice
     */
    public function show(Invoice $invoice)
    {
        $admin = auth('admin')->user();

        // Authorize using policy
        $this->authorize('viewAsAdmin', $invoice);

        $invoice->load(['order.customer', 'order.items.design', 'order.items.size', 'order.items.options.option']);

        return view('content.invoices.show', [
            'invoice' => $invoice,
        ]);
    }
}

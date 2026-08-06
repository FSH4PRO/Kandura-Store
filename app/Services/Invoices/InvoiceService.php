<?php

namespace App\Services\Invoices;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generatePdf(Invoice $invoice): string
    {
        $invoice->load([
            'order.customer.user',
            'order.address',    
            'order.items.design',
            'order.items.size',
            'order.items.options.option',
            'order.coupon', 
        ]);
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        return Storage::disk('public')->url($filename);
    }
}

<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Invoice;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Services\Invoices\InvoiceService;

class OrderObserver
{
  public function created(Order $order): void
  {
    event(new OrderCreated($order));
  } 

  public function updated(Order $order)
  {
    if ($order->wasChanged('status')) {
      event(new OrderStatusChanged(
        $order,
        $order->getOriginal('status')->value,
        $order->status->value
      ));
    }


    if ($order->wasChanged('payment_status') && $order->payment_status->value === 'paid') {
      $this->createInvoice($order);
    }
  }

  private function createInvoice(Order $order)
  {
    if ($order->invoice) {
      return; // Already has invoice
    }

    $invoiceNumber = 'INV-' . strtoupper(uniqid());

    $invoice = Invoice::create([
      'invoice_number' => $invoiceNumber,
      'order_id' => $order->id,
      'total' => $order->total,
      'pdf_url' => null,
    ]);

    $invoiceService = new InvoiceService();
    $pdfUrl = $invoiceService->generatePdf($invoice);

    $invoice->update(['pdf_url' => $pdfUrl]);
  }
}

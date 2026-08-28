<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Invoice;

class InvoicePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('invoices.view');
    }

    public function viewAsAdmin(Admin $admin, Invoice $invoice): bool
    {
        return $admin->can('invoices.view');
    }
}

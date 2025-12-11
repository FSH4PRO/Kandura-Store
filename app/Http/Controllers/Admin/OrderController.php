<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;

        // لو عندك ميدلوير للصلاحيات ضيفه هنا
        // $this->middleware('check.role:manage_orders,super_admin');
    }

    /**
     * Orders index (admin side)
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search'     => ['nullable', 'string', 'max:255'],
            'status'     => ['nullable', Rule::in(array_column(OrderStatus::cases(), 'value'))],
            'total_min'  => ['nullable', 'numeric', 'min:0'],
            'total_max'  => ['nullable', 'numeric', 'min:0'],
            'per_page'   => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by'    => ['nullable', 'in:id,created_at,total_amount'],
            'sort_dir'   => ['nullable', 'in:asc,desc'],
        ]);

        $orders = $this->service->list($filters);
        $statusOptions = OrderStatus::cases();

        return view('content.orders.index', [
            'orders'        => $orders,
            'filters'       => $filters,
            'statusOptions' => $statusOptions,
        ]);
    }

    /**
     * Show single order
     */
    public function show(Order $order)
    {
        $order->load(['customer.user', 'items.design', 'items.size', 'items.options.option']);

        $statusOptions = OrderStatus::cases();

        return view('content.orders.show', [
            'order'         => $order,
            'statusOptions' => $statusOptions,
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_column(OrderStatus::cases(), 'value'))],
        ]);

        $this->service->updateStatus($order, $data['status']);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', __('orders.messages.status_updated'));
    }
}

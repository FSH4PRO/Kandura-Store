<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\OrderService;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\ListOrdersRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;

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
    public function index(ListOrdersRequest $request)
    {
        $admin = auth('admin')->user();

        // Authorize using policy
        $this->authorize('viewAny', Order::class);

        $filters = $request->validated();

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
        $admin = auth('admin')->user();

        // Authorize using policy
        $this->authorize('viewAsAdmin', $order);

        $order->load(['customer.user', 'items.design', 'items.size', 'items.options.option', 'coupon']);

        $statusOptions = OrderStatus::cases();

        return view('content.orders.show', [
            'order'         => $order,
            'statusOptions' => $statusOptions,
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $admin = auth('admin')->user();

        // Authorize using policy
        $this->authorize('updateStatus', $order);

        $data = $request->validated();

        $this->service->updateStatus($order, $data['status']);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', __('orders.messages.status_updated'));
    }
}

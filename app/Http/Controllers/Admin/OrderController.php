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
    }


    public function index(ListOrdersRequest $request)
    {
        $admin = auth('admin')->user();

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

    public function show(Order $order)
    {
        $admin = auth('admin')->user();


        $this->authorize('viewAsAdmin', $order);

        $order->load(['customer.user', 'items.design', 'items.size', 'items.options.option', 'coupon', 'review']);

        $statusOptions = OrderStatus::cases();

        return view('content.orders.show', [
            'order'         => $order,
            'statusOptions' => $statusOptions,
        ]);
    }


    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $admin = auth('admin')->user();

        $this->authorize('updateStatus', $order);

        $data = $request->validated();

        $this->service->updateStatus($order, $data['status']);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', __('orders.messages.status_updated'));
    }
}

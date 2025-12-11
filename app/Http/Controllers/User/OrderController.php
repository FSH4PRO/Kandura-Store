<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Services\User\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Order\StoreOrderRequest;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }


    public function index()
    {
        $customer = auth('customer')->user();

        $orders = $this->service->orderList($customer);

        return $this->success(
            OrderResource::collection($orders)->response()->getData(true),
            __('messages.order_list'),
        );
    }


    public function store(StoreOrderRequest $request)
    {
        $customer = auth('customer')->user();

        $order = $this->service->createOrder($customer, $request->validated());

        return $this->success(
            new OrderResource($order),
            __('messages.order_created'),
            201
        );
    }


    public function show(Order $order)
    {
        $customer = auth('customer')->user();

        $this->authorize('view', $order);

        $order->load([
            'items.design',
            'items.size',
            'items.options.option',
        ]);

        return $this->success(
            new OrderResource($order),
            __('messages.order_details'),
            200
        );
    }


    public function cancel(Order $order) 
    {
        $customer = auth('customer')->user();

        $this->authorize('cancel', $order);

        if ( $order->status != OrderStatus::Pending) {
            return $this->failed('Cannot cancel this order in current status',null, 422);
        }

        $order->update([
            'status' => OrderStatus::Canceled->value,
        ]);

        return $this->success(
            new OrderResource($order->fresh()),
            __('messages.order_canceled')
        );
    }
}
